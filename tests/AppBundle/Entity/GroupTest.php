<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Group;
use AppBundle\Entity\User;
use Tests\AppBundle\KernelTest;

/**
 * Class GroupTest.
 */
class GroupTest extends KernelTest
{
    /**
     * Tests getters and setters of Group class.
     */
    public function testGetterAndSetter()
    {
        $user = new User();
        $user->setEmail('test@test.com')->setGoogleId(123);
        $group = new Group('test', 'ROLE_TEST');

        $group->setUsers([]);
        $group->addUser($user);

        $this->assertNull($group->getId());
        $this->assertEquals('test', $group->getName());
        $this->assertEquals('ROLE_TEST', $group->getRole());

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $em->persist($group);
        $em->flush();

        $group = $em->getRepository('AppBundle:Group')->findOneBy(['name' => 'test']);

        $this->assertEquals(1, count($group->getUsers()));
        $this->assertNotNull($group->getId());
    }

    /**
     * Tests serialization of the Group class.
     */
    public function testSerialization()
    {
        $group = new Group('test', 'TEST_ROLE');
        $serialized = $group->serialize();

        $this->assertTrue(is_string($serialized));

        $group1 = new Group('blub', 'Sskdfldf');
        $group1->unserialize($serialized);

        $this->assertNull($group1->getId());
        $this->assertEquals('test', $group1->getName());
        $this->assertEquals('TEST_ROLE', $group1->getRole());
    }
}
