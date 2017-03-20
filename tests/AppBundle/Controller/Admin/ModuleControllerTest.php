<?php

namespace Tests\AppBundle\Controller\Admin;

use AppBundle\Entity\Module;
use Tests\AppBundle\KernelTest;

/**
 * Class ModuleControllerTest.
 *
 *
 * @covers \AppBundle\Controller\Admin\ModuleController
 */
class ModuleControllerTest extends KernelTest
{
    public function setUp()
    {
        parent::setup();
        $this->client = static::createClient();
    }

    public function testIndex()
    {
        $this->logIn('ROLE_ADMIN');

        $crawler = $this->client->request('GET', '/admin/module/');

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Module")')->count());
    }

    public function testCreate()
    {
        $this->logIn('ROLE_ADMIN');

        $crawler = $this->client->request('GET', '/admin/module/create');

        $form = $crawler->selectButton('submit')->form();
        $form['appbundle_module[name]'] = 'Test Module';
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirection());
    }

    public function testShow()
    {
        $this->logIn('ROLE_ADMIN');

        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        // Create Module
        $module = new Module();
        $module->setName('Automated-Test-Module');
        $entityManager->persist($module);
        $entityManager->flush();

        // Get Module
        $crawler = $this->client->request('GET', '/admin/module/show/'.$module->getId());

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Name:")')->count());

        /** @ToDo: Test for not existing module */
        // Not existing Module
//        $crawler = $this->client->request('GET', '/admin/module/show/0');
//        $this->assertTrue($this->client->getResponse()->isNotFound());
    }

    public function testEdit()
    {
        $this->logIn('ROLE_ADMIN');

        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        // Create Module
        $module = new Module();
        $module->setName('Automated-Test-Module');
        $entityManager->persist($module);
        $entityManager->flush();

        // Get Module
        $crawler = $this->client->request('GET', '/admin/module/edit/'.$module->getId());

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $form = $crawler->selectButton('submit')->form();
        $form['appbundle_module[name]'] = 'My Test Module';
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect('/admin/module/show/'.$module->getId()));
    }
}
