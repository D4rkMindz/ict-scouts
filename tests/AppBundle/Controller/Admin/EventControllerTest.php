<?php

namespace Tests\AppBundle\Controller\Admin;

use AppBundle\Entity\Event;
use Tests\AppBundle\KernelTest;

/**
 * Class EventControllerTest.
 *
 *
 * @covers \AppBundle\Controller\Admin\EventController
 */
class EventControllerTest extends KernelTest
{
    public function setUp()
    {
        parent::setup();
        $this->client = static::createClient();
    }

    public function testIndex()
    {
        $this->logIn('ROLE_ADMIN');

        $crawler = $this->client->request('GET', '/admin/event/');

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Events")')->count());
    }

    public function testCreate()
    {
        $this->logIn('ROLE_ADMIN');

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
        $this->logIn('ROLE_ADMIN');

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
        $this->logIn('ROLE_ADMIN');

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
}
