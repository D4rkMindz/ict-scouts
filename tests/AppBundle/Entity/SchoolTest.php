<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Address;
use AppBundle\Entity\Province;
use AppBundle\Entity\School;
use AppBundle\Entity\Zip;
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

        $province = new Province('Baselland', 'BL');
        $zip = new Zip('4133', 'Pratteln');
        $address = new Address('Hauptstrasse 11', $zip, $province);

        $school = new School('Global School');
        $school->setName('Global School');
        $school->setAddress($address);

        $this->assertNull($school->getId());
        $this->assertEquals('Global School', $school->getName());
        $this->assertEquals($address, $school->getAddress());

        $entityManager->persist($province);
        $entityManager->persist($address);
        $entityManager->persist($school);
        $entityManager->flush();

        $this->assertEquals(1, $school->getId());
    }

    /**
     * Tests serialization of the Group class.
     */
    public function testSerialization()
    {
        $province = new Province('Baselland', 'BL');
        $zip = new Zip('4133', 'Pratteln');
        $address = new Address('Hauptstrasse 11', $zip, $province);

        $school = new School('Global School');
        $school->setAddress($address);

        $serialized = $school->serialize();

        $this->assertTrue(is_string($serialized));

        $newSchool = new School('');
        $newSchool->unserialize($serialized);

        $this->assertInstanceOf(School::class, $newSchool);
        $this->assertEquals(null, $newSchool->getId());
        $this->assertEquals('Global School', $newSchool->getName());
    }
}
