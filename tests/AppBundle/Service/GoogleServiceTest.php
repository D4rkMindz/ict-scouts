<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Service\GoogleService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GoogleServiceTest extends WebTestCase
{
    public function testGetAllUsers()
    {
        $client = static::createClient();

        /** @var GoogleService $googleService */
        $googleService = $client->getContainer()->get('app.service.google');
        $googleService->getAllUsers($client->getContainer()->getParameter('google_apps_domain'));

        $user = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:User')->findOneBy(['email' => $googleService->getAdminUser()]);

        $this->assertNotNull($user);

        $users = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:User')->findAll();

        $this->assertGreaterThanOrEqual(2, count($users));
    }

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

    public function testCreateUser()
    {
        $client = static::createClient();

        $googleUser = new \Google_Service_Oauth2_Userinfoplus();
        $googleUser->setEmail('jane.doe@example.com');
        $googleUser->setFamilyName('Doe');
        $googleUser->setGivenName('Jane');
        $googleUser->setId('123456789011');

        $user = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:User')->findOneBy(['googleId' => $googleUser->getId()]);

        $this->assertNull($user);

        /** @var GoogleService $googleService */
        $googleService = $client->getContainer()->get('app.service.google');
        $googleService->createUser($googleUser);

        $user = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:User')->findOneBy(['googleId' => $googleUser->getId()]);

        $this->assertNotNull($user);
    }

    public function testUpdateUserData()
    {
        $client = static::createClient();

        $googleUser = new \Google_Service_Oauth2_Userinfoplus();
        $googleUser->setEmail('jane.doe@example.com');
        $googleUser->setFamilyName('Doe');
        $googleUser->setGivenName('Jane');
        $googleUser->setId('123456789011');

        /** @var GoogleService $googleService */
        $googleService = $client->getContainer()->get('app.service.google');
        $googleService->createUser($googleUser);

        /** @var User $user */
        $user = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:User')->findOneBy(['googleId' => $googleUser->getId()]);

        $this->assertNotNull($user);
        $this->assertNull($user->getAccessToken());
        $this->assertNull($user->getAccessTokenExpireDate());
        $this->assertTrue($googleService->updateUserAccessToken($googleUser->getId(), ['access_token' => 'abc123cba', 'expires_in' => '3599']));

        $user = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:User')->findOneBy(['googleId' => $googleUser->getId()]);

        $this->assertNotNull($user);
        $this->assertEquals('abc123cba', $user->getAccessToken());
        $this->assertLessThanOrEqual((new \DateTime())->add(new \DateInterval('PT3594S')), $user->getAccessTokenExpireDate());
        $this->assertFalse($googleService->updateUserAccessToken(42, ['access_token' => 'cba321abc', 'expires_in' => '3599']));
    }

    public function testUpdateUserGroups()
    {
        $client = static::createClient();

        $janeTalent = new User();
        $janeTalent->setEmail('jane.talent@example.com');
        $janeTalent->setFamilyName('Talent');
        $janeTalent->setGivenName('Jane');
        $janeTalent->setGoogleId('123456789014');

        $janeScout = new User();
        $janeScout->setEmail('jane.scout@example.com');
        $janeScout->setFamilyName('Scout');
        $janeScout->setGivenName('Jane');
        $janeScout->setGoogleId('123456789013');

        $janeAdmin = new User();
        $janeAdmin->setEmail('jane.admin@example.com');
        $janeAdmin->setFamilyName('Admin');
        $janeAdmin->setGivenName('Jane');
        $janeAdmin->setGoogleId('123456789012');

        $janeDoe = new User();
        $janeDoe->setEmail('jane.doe@example.com');
        $janeDoe->setFamilyName('Doe');
        $janeDoe->setGivenName('Jane');
        $janeDoe->setGoogleId('123456789011');

        /** @var GoogleService $googleService */
        $googleService = $client->getContainer()->get('app.service.google');
        $googleService->updateUserGroups($janeDoe, '/');
        $googleService->updateUserGroups($janeAdmin, '/Support');
        $googleService->updateUserGroups($janeScout, '/Scouts');
        $googleService->updateUserGroups($janeTalent, '/ict-campus/ICT Talents');

        $adminGroup = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_ADMIN']);
        $scoutGroup = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_SCOUT']);
        $talentGroup = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_TALENT']);

        $this->assertEmpty($janeDoe->getGroups());
        $this->assertEquals(1, count($janeAdmin->getGroups()), 'JaneAdmin');
        $this->assertContains($adminGroup, $janeAdmin->getGroups());
        $this->assertEquals(1, count($janeScout->getGroups()), 'JaneScout');
        $this->assertContains($scoutGroup, $janeScout->getGroups());
        $this->assertEquals(1, count($janeTalent->getGroups()), 'JaneTalent');
        $this->assertContains($talentGroup, $janeTalent->getGroups());
    }
}
