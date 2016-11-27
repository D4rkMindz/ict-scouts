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
            if($user->getEmail() == $googleHelper->getAdminUser()){
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

    public function testInitClient()
    {
        $client = static::createClient();

        /** @var GoogleHelper $googleHelper */
        $googleHelper = $client->getContainer()->get('app.helper.google');

        $this->assertEquals('HappyR\Google\ApiBundle\Services\GoogleClient', get_class($googleHelper->initClient(GoogleHelper::SCOPE_USER, false)));
        $this->assertEquals('Google_Client', get_class($googleHelper->initClient(GoogleHelper::SCOPE_SERVICE, true)));
    }

    public function testGetContainer()
    {
        $client = static::createClient();

        /** @var GoogleHelper $googleHelper */
        $googleHelper = $client->getContainer()->get('app.helper.google');

        $this->assertEquals($client->getContainer(), $googleHelper->getContainer());
    }
}
