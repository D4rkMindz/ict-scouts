<?php

namespace Tests\AppBundle\Controller\Admin;

use AppBundle\Entity\Event;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Tests\AppBundle\KernelTest;

/**
 * Class EventControllerTest.
 *
 *
 * @covers \AppBundle\Controller\Admin\EventController
 */
class EventControllerTest extends KernelTest
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

        $crawler = $this->client->request('GET', '/admin/event/');

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Events")')->count());
    }

    public function testCreate()
    {
        $this->logIn();

        $crawler = $this->client->request('GET', '/admin/event/create');

        $form = $crawler->selectButton('submit')->form();
        $form['appbundle_event[name]'] = 'Test Event';
        $form['appbundle_event[startDate][date][month]'] = 2;
        $form['appbundle_event[startDate][date][day]'] = 28;
        $form['appbundle_event[startDate][date][year]'] = 2017;
        $form['appbundle_event[startDate][time][hour]'] = 12;
        $form['appbundle_event[startDate][time][minute]'] = 0;
        $form['appbundle_event[endDate][date][month]'] = 2;
        $form['appbundle_event[endDate][date][day]'] = 28;
        $form['appbundle_event[endDate][date][year]'] = 2017;
        $form['appbundle_event[endDate][time][hour]'] = 13;
        $form['appbundle_event[endDate][time][minute]'] = 0;
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirection());
    }

    public function testShow()
    {
        $this->logIn();

        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        // Create Module
        $event = new Event();
        $event->setName('Automated-Test-Event');
        $event->setStartDate(new \DateTime());
        $event->setEndDate((new \DateTime())->add(new \DateInterval('P1D')));
        $entityManager->persist($event);
        $entityManager->flush();

        // Get Module
        $crawler = $this->client->request('GET', '/admin/event/show/'.$event->getId());

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Name:")')->count());

        // Not existing Module
//        $crawler = $this->client->request('GET', '/admin/module/show/0');
//        $this->assertTrue($this->client->getResponse()->isNotFound());
    }

    public function testEdit()
    {
        $this->logIn();

        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        // Create Module
        $event = new Event();
        $event->setName('Automated-Test-Event');
        $event->setStartDate(new \DateTime());
        $event->setEndDate((new \DateTime())->add(new \DateInterval('P1D')));
        $entityManager->persist($event);
        $entityManager->flush();

        // Get Module
        $crawler = $this->client->request('GET', '/admin/event/edit/'.$event->getId());

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $form = $crawler->selectButton('submit')->form();
        $form['appbundle_event[name]'] = 'Test Event';
        $form['appbundle_event[startDate][date][month]'] = 2;
        $form['appbundle_event[startDate][date][day]'] = 28;
        $form['appbundle_event[startDate][date][year]'] = 2017;
        $form['appbundle_event[startDate][time][hour]'] = 12;
        $form['appbundle_event[startDate][time][minute]'] = 0;
        $form['appbundle_event[endDate][date][month]'] = 2;
        $form['appbundle_event[endDate][date][day]'] = 28;
        $form['appbundle_event[endDate][date][year]'] = 2017;
        $form['appbundle_event[endDate][time][hour]'] = 13;
        $form['appbundle_event[endDate][time][minute]'] = 0;
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect('/admin/event/show/'.$event->getId()));
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
