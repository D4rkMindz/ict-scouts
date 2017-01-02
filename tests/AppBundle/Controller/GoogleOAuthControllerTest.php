<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Tests\AppBundle\KernelTest;

class GoogleOAuthControllerTest extends KernelTest
{
    /** @var Client */
    private $client = null;

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
