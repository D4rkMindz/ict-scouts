<?php

namespace Tests\AppBundle\Security;

use AppBundle\Entity\User;
use AppBundle\Security\GoogleAuthenticator;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Tests\AppBundle\KernelTest;

class GoogleAuthenticatorTest extends KernelTest
{
    /** @var Client */
    private $client = null;

    /** @var GoogleAuthenticator */
    private $authenticator = null;

    public function setUp()
    {
        parent::setup();
        $this->client = static::createClient();

        $this->authenticator = new GoogleAuthenticator();
    }

    public function testOnAuthenticationFailure()
    {
        $this->failingLogIn();

        $crawler = $this->client->request('GET', '/admin/');

        $this->assertFalse($this->authenticator->supportsRememberMe());
    }

    public function testStart()
    {
        $this->authenticator->start(new Request());

        $this->assertFalse($this->authenticator->supportsRememberMe());
    }

    public function testSupportsRememberMe()
    {
        $this->logIn();

        $crawler = $this->client->request('GET', '/');

        $this->assertFalse($this->authenticator->supportsRememberMe());
    }

    private function logIn()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $group = $em->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_ADMIN']);
        $firewall = 'main';
        $session = $this->getContainer()->get('session');

        /** @var User $user */
        $user = new User();
        $user->setAccessToken('abc123cba');
        $user->setAccessTokenExpireDate((new \DateTime())->add(new \DateInterval('PT3595S')));
        $user->setGoogleId(123456789);
        $user->setEmail('john.doe@'.$this->getContainer()->getParameter('google_apps_domain'));
        $user->setFamilyName('Doe');
        $user->setGivenName('John');
        $user->setGroups([$group]);

        $em->persist($user);
        $em->flush();

        $token = new UsernamePasswordToken($user->getUsername(), ['accessToken' => 'abc123cba'], $firewall, ['ROLE_ADMIN']);
        $session->set('_security_'.$firewall, serialize($token));
        $session->set('access_token', 'abc123cba');
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    private function failingLogIn()
    {
        $session = $this->getContainer()->get('session');

        $session->set('access_token', 'cba123abc');
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
