<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Service\GoogleUserService;
use Doctrine\ORM\EntityManager;
use Tests\AppBundle\KernelTest;

class GoogleUserServiceTest extends KernelTest
{

    public function testGetGoogleService()
    {
        $client = static::createClient();

        /** @var GoogleUserService $googleUserService */
        $googleUserService = $client->getContainer()->get('app.service.google.user');

        $this->assertEquals('AppBundle\Service\GoogleService', get_class($googleUserService->getGoogleService()));
    }

    public function testGetAllUsers()
    {
        $client = static::createClient();

        /** @var GoogleUserService $googleUserService */
        $googleUserService = $client->getContainer()->get('app.service.google.user');
        $googleUserService->getAllUsers($client->getContainer()->getParameter('google_apps_domain'));

        $user = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:User')->findOneBy(['email' => $googleUserService->getGoogleService()->getAdminUser()]);

        $this->assertNotNull($user);

        $users = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:User')->findAll();

        $this->assertGreaterThanOrEqual(2, count($users));
    }

    public function testUpdateUserAccessToken()
    {
        $client = static::createClient();

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        /** @var GoogleUserService $googleUserService */
        $googleUserService = $client->getContainer()->get('app.service.google.user');

        $user = new User();
        $user->setGoogleId('1234567890');
        $user->setEmail('jane.doe@example.com');
        $em->persist($user);
        $em->flush();

        $this->assertFalse($googleUserService->updateUserAccessToken('9876543210'));

        $this->assertNull($user->getAccessToken());
        $this->assertTrue($googleUserService->updateUserAccessToken('1234567890'));

        $user = $em->getRepository('AppBundle:User')->findOneBy(['googleId' => '1234567890']);
        $this->assertNull($user->getAccessToken());

        $user = $em->getRepository('AppBundle:User')->findOneBy(['googleId' => '1234567890']);
        $this->assertNull($user->getAccessToken());
        $this->assertTrue($googleUserService->updateUserAccessToken('1234567890', ['access_token' => 'abc123cba', 'expires_in' => 3600]));

        $user = $em->getRepository('AppBundle:User')->findOneBy(['googleId' => '1234567890']);
        $this->assertNull($user->getAccessToken());
    }

    public function testupdateUserGroups()
    {
        $client = static::createClient();

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        /** @var GoogleUserService $googleUserService */
        $googleUserService = $client->getContainer()->get('app.service.google.user');

        $user = new User();
        $user->setGoogleId('1234567890');
        $user->setEmail('jane.doe@example.com');
        $em->persist($user);
        $em->flush();

        $this->assertEmpty($googleUserService->updateUserGroups($user, '/not-existing-ou'));
        $this->assertCount(1, $googleUserService->updateUserGroups($user, '/ict-campus/ICT Talents'));
        $this->assertCount(2, $googleUserService->updateUserGroups($user, '/Scouts'));
        $this->assertCount(3, $googleUserService->updateUserGroups($user, '/Support'));
    }
}
