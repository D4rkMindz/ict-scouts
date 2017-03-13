<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Group;
use AppBundle\Entity\User;
use Tests\AppBundle\KernelTest;

/**
 * Class GroupTest.
 *
 * @covers \AppBundle\Entity\Group
 */
class GroupTest extends KernelTest
{
    /**
     * Tests getters and setters of Group class.
     */
    public function testGetterAndSetter()
    {
        $user = new User('123', 'test@test.com', '');
        $group = new Group('test', 'ROLE_TEST');

        $group->addUser($user);

        $this->assertNull($group->getId());
        $this->assertEquals('test', $group->getName());
        $this->assertEquals('ROLE_TEST', $group->getRole());

        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $entityManager->persist($group);
        $entityManager->flush();

        $group = $entityManager->getRepository('AppBundle:Group')->findOneBy(['name' => 'test']);

        $this->assertCount(1, $group->getUsers());
        $this->assertNotNull($group->getId());

        $group->removeUser($user);
        $this->assertCount(0, $group->getUsers());
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
