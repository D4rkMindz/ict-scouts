<?php

namespace Tests\AppBundle\Service;

use AppBundle\Service\GoogleUserService;
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
}
