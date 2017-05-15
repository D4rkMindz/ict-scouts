<?php

namespace Tests\AppBundle\Controller;

use Tests\AppBundle\KernelTest;

/**
 * Class GoogleOAuthControllerTest.
 *
 *
 * @covers \AppBundle\Controller\GoogleOAuthController
 */
class GoogleOAuthControllerTest extends KernelTest
{
    public function setUp()
    {
        parent::setup();
        $this->client = static::createClient();
    }

    public function testLogin()
    {
        $crawler = $this->client->request('GET', '/login');

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("https://accounts.google.com/")')->count());
    }
}
