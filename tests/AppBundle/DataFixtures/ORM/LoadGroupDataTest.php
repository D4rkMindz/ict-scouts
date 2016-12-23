<?php

namespace AppBundle\DataFixtures\ORM;

use Tests\AppBundle\KernelTest;


/**
 * Class LoadGroupDataTest.
 */
class LoadGroupDataTest extends KernelTest
{
    /**
     * Tests load function.
     */
    public function testLoad()
    {
        $repo = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:Group');
        $scout = $repo->findOneBy(['name' => 'Scout']);
        $talent = $repo->findOneBy(['name' => 'Talent']);
        $admin = $repo->findOneBy(['name' => 'Admin']);

        $this->assertEquals($scout->getRole(), 'ROLE_SCOUT');
        $this->assertEquals($talent->getRole(), 'ROLE_TALENT');
        $this->assertEquals($admin->getRole(), 'ROLE_ADMIN');
    }
}