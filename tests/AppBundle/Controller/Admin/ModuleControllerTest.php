<?php

namespace Tests\AppBundle\Controller\Admin;

use AppBundle\Entity\Module;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Tests\AppBundle\KernelTest;

/**
 * Class ModuleControllerTest.
 *
 *
 * @covers \AppBundle\Controller\Admin\ModuleController
 */
class ModuleControllerTest extends KernelTest
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

        $crawler = $this->client->request('GET', '/admin/module/');

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Module")')->count());
    }

    public function testCreate()
    {
        $this->logIn();

        $crawler = $this->client->request('GET', '/admin/module/create');

        $form = $crawler->selectButton('submit')->form();
        $form['appbundle_module[name]'] = 'Test Module';
        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirection());
    }

    public function testShow()
    {
        $this->logIn();

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        // Create Module
        $module = new Module();
        $module->setName('Automated-Test-Module');
        $em->persist($module);
        $em->flush();

        // Get Module
        $crawler = $this->client->request('GET', '/admin/module/show/'.$module->getId());

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Name:")')->count());

        // Not existing Module
//        $crawler = $this->client->request('GET', '/admin/module/show/0');
//        $this->assertTrue($this->client->getResponse()->isNotFound());
    }

    public function testEdit()
    {
        $this->logIn();

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        // Create Module
        $module = new Module();
        $module->setName('Automated-Test-Module');
        $em->persist($module);
        $em->flush();

        // Get Module
        $crawler = $this->client->request('GET', '/admin/module/edit/'.$module->getId());

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $form = $crawler->selectButton('submit')->form();
        $form['appbundle_module[name]'] = 'My Test Module';
        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect('/admin/module/show/'.$module->getId()));
    }

    private function logIn()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $group = $em->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_ADMIN']);
        $firewall = 'main';
        $session = $this->getContainer()->get('session');

        /** @var User $user */
        $user = new User('123456789', 'john.doe@'.$this->getContainer()->getParameter('google_apps_domain'), 'abc123cba');
        $user->setAccessTokenExpireDate((new \DateTime())->add(new \DateInterval('PT3595S')));
        $user->addGroup($group);

        $em->persist($user);
        $em->flush();

        $token = new UsernamePasswordToken($user->getUsername(), ['accessToken' => 'abc123cba'], $firewall, ['ROLE_ADMIN']);
        $session->set('_security_'.$firewall, serialize($token));
        $session->set('access_token', 'abc123cba');
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
