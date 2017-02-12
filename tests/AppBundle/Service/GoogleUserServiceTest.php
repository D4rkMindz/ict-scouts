<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Service\GoogleUserService;
use Doctrine\ORM\EntityManager;
use Tests\AppBundle\KernelTest;

/**
 * Class GoogleUserServiceTest.
 *
 *
 * @covers \AppBundle\Service\GoogleUserService
 */
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

        $user = new User('123456789', 'jane.doe@example.com');
        $em->persist($user);
        $em->flush();

        $this->assertFalse($googleUserService->updateUserAccessToken('9876543210'));
        $this->assertNull($user->getAccessToken());
        $this->assertTrue($googleUserService->updateUserAccessToken('123456789'));

        $user = $em->getRepository('AppBundle:User')->findOneBy(['googleId' => '123456789']);
        $this->assertNull($user->getAccessToken());

        $user = $em->getRepository('AppBundle:User')->findOneBy(['googleId' => '123456789']);
        $this->assertNull($user->getAccessToken());
        $this->assertTrue($googleUserService->updateUserAccessToken('123456789', ['access_token' => 'abc123cba', 'expires_in' => 3600]));

        $user = $em->getRepository('AppBundle:User')->findOneBy(['googleId' => '123456789']);
        $this->assertNull($user->getAccessToken());
    }

    public function testUpdateUserGroups()
    {
        $client = static::createClient();

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        /** @var GoogleUserService $googleUserService */
        $googleUserService = $client->getContainer()->get('app.service.google.user');

        $user = new User('111222333444', 'john.doe@example.com');
        $em->persist($user);
        $em->flush();

        $googleUserService->updateUserGroups($user, '/ict-campus/ICT Talents');
        $this->assertCount(1, $user->getGroups());
        $googleUserService->updateUserGroups($user, '/Scouts');
        $this->assertCount(2, $user->getGroups());
        $googleUserService->updateUserGroups($user, '/Support');
        $this->assertCount(3, $user->getGroups());
    }
}
