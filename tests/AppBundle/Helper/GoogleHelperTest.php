<?php

namespace Tests\AppBundle\Helper;

use AppBundle\Helper\GoogleHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GoogleHelperTest extends WebTestCase
{
    public function testGetAllUsers()
    {
        $client = static::createClient();

        /** @var GoogleHelper $googleHelper */
        $googleHelper = $client->getContainer()->get('app.helper.google');

        $this->assertCount(2, $googleHelper->getAllUsers($client->getContainer()->getParameter('google_apps_domain')));
    }

    public function testGetUserScopes()
    {
        $client = static::createClient();

        /** @var GoogleHelper $googleHelper */
        $googleHelper = $client->getContainer()->get('app.helper.google');

        $expectedScopes = [
            \Google_Service_Oauth2::USERINFO_PROFILE,
            \Google_Service_Oauth2::USERINFO_EMAIL,
        ];

        $this->assertEquals($expectedScopes, $googleHelper->getUserScopes());
    }

    public function testGetServiceScopes()
    {
        $client = static::createClient();

        /** @var GoogleHelper $googleHelper */
        $googleHelper = $client->getContainer()->get('app.helper.google');

        $expectedScopes = [
            \Google_Service_Directory::ADMIN_DIRECTORY_USER_READONLY,
            \Google_Service_Directory::ADMIN_DIRECTORY_GROUP_READONLY,
        ];

        $this->assertEquals($expectedScopes, $googleHelper->getServiceScopes());
    }

    public function testInitClient()
    {
        $client = static::createClient();

        /** @var GoogleHelper $googleHelper */
        $googleHelper = $client->getContainer()->get('app.helper.google');

        $this->assertEquals('HappyR\Google\ApiBundle\Services\GoogleClient', get_class($googleHelper->initClient(false)));
        $this->assertEquals('Google_Client', get_class($googleHelper->initClient(true)));
    }

    public function testGetContainer()
    {
        $client = static::createClient();

        /** @var GoogleHelper $googleHelper */
        $googleHelper = $client->getContainer()->get('app.helper.google');

        $this->assertEquals($client->getContainer(), $googleHelper->getContainer());
    }
}
