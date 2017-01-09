<?php

namespace Tests\AppBundle\Helper;

use AppBundle\Entity\User;
use AppBundle\Helper\GoogleHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GoogleHelperTest extends WebTestCase
{
    public function testGetAllUsers()
    {
        $client = static::createClient();

        /** @var GoogleHelper $googleHelper */
        $googleHelper = $client->getContainer()->get('app.helper.google');
        $googleHelper->getAllUsers($client->getContainer()->getParameter('google_apps_domain'));

        $user = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:User')->findOneBy(['email' => $googleHelper->getAdminUser()]);

        $this->assertNotNull($user);

        $users = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:User')->findAll();

        $this->assertGreaterThanOrEqual(2, count($users));
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

        /** @var GoogleHelper $googleHelper */
        $googleHelper = $client->getContainer()->get('app.helper.google');
        $googleHelper->createUser($googleUser);

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

        /** @var GoogleHelper $googleHelper */
        $googleHelper = $client->getContainer()->get('app.helper.google');
        $googleHelper->createUser($googleUser);

        /** @var User $user */
        $user = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:User')->findOneBy(['googleId' => $googleUser->getId()]);

        $this->assertNotNull($user);
        $this->assertNull($user->getAccessToken());
        $this->assertNull($user->getAccessTokenExpireDate());
        $this->assertTrue($googleHelper->updateUserAccessToken($googleUser->getId(), ['access_token' => 'abc123cba', 'expires_in' => '3599']));

        $user = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:User')->findOneBy(['googleId' => $googleUser->getId()]);

        $this->assertNotNull($user);
        $this->assertEquals('abc123cba', $user->getAccessToken());
        $this->assertLessThanOrEqual((new \DateTime())->add(new \DateInterval('PT3594S')), $user->getAccessTokenExpireDate());
        $this->assertFalse($googleHelper->updateUserAccessToken(42, ['access_token' => 'cba321abc', 'expires_in' => '3599']));
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

        /** @var GoogleHelper $googleHelper */
        $googleHelper = $client->getContainer()->get('app.helper.google');
        $googleHelper->updateUserGroups($janeDoe, '/');
        $googleHelper->updateUserGroups($janeAdmin, '/Support');
        $googleHelper->updateUserGroups($janeScout, '/Scouts');
        $googleHelper->updateUserGroups($janeTalent, '/ict-campus/ICT Talents');

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
