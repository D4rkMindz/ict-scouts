<?php

namespace Tests\AppBundle\Controller;

use Tests\AppBundle\KernelTest;

/**
 * Class DefaultControllerTest.
 *
 *
 * @covers \AppBundle\Controller\AppController
 */
class AppControllerTest extends KernelTest
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        //$this->assertContains('Welcome to Symfony', $crawler->filter('#container h1')->text());
    }
}
