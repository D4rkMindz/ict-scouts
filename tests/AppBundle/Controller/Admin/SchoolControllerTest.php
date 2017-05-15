<?php

namespace Tests\AppBundle\Controller\Admin;

use AppBundle\Entity\School;
use Tests\AppBundle\KernelTest;

/**
 * Class SchoolControllerTest.
 *
 *
 * @covers \AppBundle\Controller\Admin\SchoolController
 */
class SchoolControllerTest extends KernelTest
{
    public function setUp()
    {
        parent::setup();
        $this->client = static::createClient();
    }

    public function testIndex()
    {
        $this->logIn('ROLE_ADMIN');

        $crawler = $this->client->request('GET', '/admin/school/');

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Schulen")')->count());
    }

    public function testCreate()
    {
        $this->logIn('ROLE_ADMIN');

        $crawler = $this->client->request('GET', '/admin/school/create');

        $form = $crawler->selectButton('submit')->form();
        $form['appbundle_school[name]'] = 'Test School';
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirection());
    }

    public function testShow()
    {
        $this->logIn('ROLE_ADMIN');

        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        // Create Module
        $module = new School('Automated-Test-School');
        $entityManager->persist($module);
        $entityManager->flush();

        // Get Module
        $crawler = $this->client->request('GET', '/admin/school/show/'.$module->getId());

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
        $module = new School('Automated-Test-School');
        $entityManager->persist($module);
        $entityManager->flush();

        // Get Module
        $crawler = $this->client->request('GET', '/admin/school/edit/'.$module->getId());

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $form = $crawler->selectButton('submit')->form();
        $form['appbundle_school[name]'] = 'My Test School';
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect('/admin/school/show/'.$module->getId()));
    }
}
