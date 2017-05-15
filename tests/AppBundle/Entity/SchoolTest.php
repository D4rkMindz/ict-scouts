<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\School;
use Tests\AppBundle\KernelTest;

/**
 * Class SchoolTest.
 *
 * @covers \AppBundle\Entity\School
 */
class SchoolTest extends KernelTest
{
    /**
     * Tests getters and setters of Person class.
     */
    public function testGetterAndSetter()
    {
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $school = new School('Global School');
        $school->setName('Global School');

        $this->assertNull($school->getId());
        $this->assertEquals('Global School', $school->getName());

        $entityManager->persist($school);
        $entityManager->flush();

        $this->assertEquals(1, $school->getId());
    }

    /**
     * Tests serialization of the Group class.
     */
    public function testSerialization()
    {
        $school = new School('Global School');

        $serialized = $school->serialize();

        $this->assertTrue(is_string($serialized));

        $newSchool = new School('');
        $newSchool->unserialize($serialized);

        $this->assertInstanceOf(School::class, $newSchool);
        $this->assertEquals(null, $newSchool->getId());
        $this->assertEquals('Global School', $newSchool->getName());
    }
}
