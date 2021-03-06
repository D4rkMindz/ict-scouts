<?php

namespace Tests\AppBundle\Service;

use AppBundle\Service\GoogleService;
use Tests\AppBundle\KernelTest;

/**
 * Class GoogleServiceTest.
 *
 * @covers \AppBundle\Service\GoogleService
 */
class GoogleServiceTest extends KernelTest
{
    public function testGetUserScopes()
    {
        $client = static::createClient();

        /** @var GoogleService $googleService */
        $googleService = $client->getContainer()->get('app.service.google');

        $expectedScopes = [
            \Google_Service_Oauth2::USERINFO_PROFILE,
            \Google_Service_Oauth2::USERINFO_EMAIL,
        ];

        $this->assertEquals($expectedScopes, $googleService->getUserScopes());
    }

    public function testGetServiceScopes()
    {
        $client = static::createClient();

        /** @var GoogleService $googleService */
        $googleService = $client->getContainer()->get('app.service.google');

        $expectedScopes = [
            \Google_Service_Directory::ADMIN_DIRECTORY_USER_READONLY,
            \Google_Service_Directory::ADMIN_DIRECTORY_GROUP_READONLY,
        ];

        $this->assertEquals($expectedScopes, $googleService->getServiceScopes());
    }

    public function testSetScopeUser()
    {
        $client = static::createClient();

        /** @var GoogleService $googleService */
        $googleService = $client->getContainer()->get('app.service.google');
        $googleService->setScope($googleService::USER);

        $this->assertEquals($googleService->getUserScopes(), $googleService->getClient()->getScopes());
    }

    public function testSetScopeService()
    {
        $client = static::createClient();

        /** @var GoogleService $googleService */
        $googleService = $client->getContainer()->get('app.service.google');
        $googleService->setScope($googleService::SERVICE);

        $this->assertEquals($googleService->getServiceScopes(), $googleService->getClient()->getScopes());
    }

    public function testGetClient()
    {
        $client = static::createClient();

        /** @var GoogleService $googleService */
        $googleService = $client->getContainer()->get('app.service.google');

        $this->assertEquals('Google_Client', get_class($googleService->getClient()));
    }

    /**
     * @expectedException \Exception
     */
    public function testAuthException()
    {
        $client = static::createClient();

        /** @var GoogleService $googleService */
        $googleService = $client->getContainer()->get('app.service.google');

        $googleService->auth('foo');
    }

    public function testAuthService()
    {
        $client = static::createClient();

        /** @var GoogleService $googleService */
        $googleService = $client->getContainer()->get('app.service.google');
        $googleService->auth($googleService::SERVICE);

        $this->assertEquals('', $googleService->getClient()->getClientId());
    }

    public function testAuthUser()
    {
        $client = static::createClient();

        /** @var GoogleService $googleService */
        $googleService = $client->getContainer()->get('app.service.google');
        $googleService->auth($googleService::USER);

        $this->assertEquals($client->getContainer()->getParameter('google_client_id'), $googleService->getClient()->getClientId());
    }

    public function testGetAdminUser()
    {
        $googleService = $this->getContainer()->get('app.service.google');

        $this->assertEquals($this->getContainer()->getParameter('google_apps_admin'), $googleService->getAdminUser());
    }
}
