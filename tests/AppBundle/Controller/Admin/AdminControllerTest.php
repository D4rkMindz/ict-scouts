<?php

namespace Tests\AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Tests\AppBundle\KernelTest;

class AdminControllerTest extends KernelTest
{
    /** @var Client */
    private $client = null;

    public function setUp()
    {
        parent::setup();
        $this->client = static::createClient();
    }

    public function testIndex()
    {
        $this->logIn();

        $crawler = $this->client->request('GET', '/admin/');

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Admin")')->count());
    }

    public function testUserSync()
    {
        $this->logIn();

        $crawler = $this->client->request('GET', '/admin/user/sync');

        $this->assertTrue($this->client->getResponse()->isRedirect('/admin/'));
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
        $user->setCreatedAt(new \DateTime());
        $user->setDeletedAt(null);
        $user->setGoogleId(123456789);
        $user->setEmail('john.doe@'.$this->getContainer()->getParameter('google_apps_domain'));
        $user->setFamilyName('Doe');
        $user->setGivenName('John');
        $user->setGroups([$group]);
        $user->setUpdatedAt(new \DateTime());

        $em->persist($user);
        $em->flush();

        $token = new UsernamePasswordToken($user->getUsername(), ['accessToken' => 'abc123cba'], $firewall, array('ROLE_ADMIN'));
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
