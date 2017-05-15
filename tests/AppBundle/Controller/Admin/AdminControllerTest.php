<?php

namespace Tests\AppBundle\Controller\Admin;

use Tests\AppBundle\KernelTest;

/**
 * Class AdminControllerTest.
 *
 *
 * @covers \AppBundle\Controller\Admin\AdminController
 */
class AdminControllerTest extends KernelTest
{
    public function setUp()
    {
        parent::setup();
        $this->client = static::createClient();
    }

    public function testIndex()
    {
        $this->logIn('ROLE_ADMIN');

        $crawler = $this->client->request('GET', '/admin/');

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Admin")')->count());
    }
}
