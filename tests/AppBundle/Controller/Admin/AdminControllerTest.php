<?php

namespace Tests\AppBundle\Controller\Admin;

use AppBundle\AppBundle;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Tests\AppBundle\KernelTest;

/**
 * Class AdminControllerTest.
 *
 *
 * @covers \AppBundle\Controller\Admin\AdminController
 */
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

        $this->client->request('GET', '/admin/user/sync');

        $this->assertTrue($this->client->getResponse()->isRedirect('/admin/'));
    }

    private function logIn()
    {
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $group = $entityManager->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_ADMIN']);
        $firewall = 'main';
        $session = $this->getContainer()->get('session');

        /** @var User $user */
        $user = new User('123456789', 'john.doe@'.$this->getContainer()->getParameter('google_apps_domain'), 'abc123cba');
        $user->setAccessTokenExpireDate((new \DateTime())->add(new \DateInterval('PT3595S')));
        $user->addGroup($group);

        $entityManager->persist($user);
        $entityManager->flush();

        $token = new UsernamePasswordToken($user->getUsername(), ['accessToken' => 'abc123cba'], $firewall, ['ROLE_ADMIN']);
        $session->set('_security_'.$firewall, serialize($token));
        $session->set('access_token', 'abc123cba');
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
