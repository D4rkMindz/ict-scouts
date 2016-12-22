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

        $users = $googleHelper->getAllUsers($client->getContainer()->getParameter('google_apps_domain'));

        /** @var \Google_Service_Oauth2_Userinfoplus $user */
        foreach ($users as $user) {
            if ($user->getEmail() == $googleHelper->getAdminUser()) {
                $googleHelper->updateUserData($user, ['access_token' => 'abc123cba', 'expires_in' => '3600']);
            } else {
                $googleHelper->updateUserData($user);
            }
        }

        $this->assertCount(2, $users);
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

    public function testSetScopeUser()
    {
        $client = static::createClient();

        /** @var GoogleHelper $googleHelper */
        $googleHelper = $client->getContainer()->get('app.helper.google');

        $googleHelper->setScope($googleHelper::USER);
        $this->assertEquals($googleHelper->getUserScopes(), $googleHelper->getClient()->getScopes());
    }

    public function testSetScopeService()
    {
        $client = static::createClient();

        /** @var GoogleHelper $googleHelper */
        $googleHelper = $client->getContainer()->get('app.helper.google');

        $googleHelper->setScope($googleHelper::SERVICE);
        $this->assertEquals($googleHelper->getServiceScopes(), $googleHelper->getClient()->getScopes());
    }

    public function testGetClient()
    {
        $client = static::createClient();

        /** @var GoogleHelper $googleHelper */
        $googleHelper = $client->getContainer()->get('app.helper.google');

        $this->assertEquals('Google_Client', get_class($googleHelper->getClient()));
    }

    /**
     * @expectedException \Exception
     */
    public function testAuthException()
    {
        $client = static::createClient();

        /** @var GoogleHelper $googleHelper */
        $googleHelper = $client->getContainer()->get('app.helper.google');

        $googleHelper->auth('foo');
    }

    public function testAuthService()
    {
        $client = static::createClient();

        /** @var GoogleHelper $googleHelper */
        $googleHelper = $client->getContainer()->get('app.helper.google');

        $googleHelper->auth($googleHelper::SERVICE);

        $this->assertEquals('', $googleHelper->getClient()->getClientId());
    }

    public function testAuthUser()
    {
        $client = static::createClient();

        /** @var GoogleHelper $googleHelper */
        $googleHelper = $client->getContainer()->get('app.helper.google');

        $googleHelper->auth($googleHelper::USER);

        $this->assertEquals($client->getContainer()->getParameter('google_client_id'), $googleHelper->getClient()->getClientId());
    }
}
