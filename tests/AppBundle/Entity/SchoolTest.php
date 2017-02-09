<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Address;
use AppBundle\Entity\Province;
use AppBundle\Entity\School;
use Tests\AppBundle\KernelTest;

/**
 * Class PersonTest.
 */
class SchoolTest extends KernelTest
{
    /**
     * Tests getters and setters of Person class.
     */
    public function testGetterAndSetter()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $province = new Province('Baselland', 'BL');
        $address = new Address($province, 'Pratteln', 'Hauptstrasse', '11');

        $school = new School('Global School');
        $school->setName('Global School');
        $school->setAddress($address);

        $this->assertNull($school->getId());
        $this->assertEquals('Global School', $school->getName());
        $this->assertEquals($address, $school->getAddress());

        $em->persist($province);
        $em->persist($address);
        $em->persist($school);
        $em->flush();

        $this->assertEquals(1, $school->getId());
    }

    /**
     * Tests serialization of the Group class.
     */
    public function testSerialization()
    {
        $province = new Province('Baselland', 'BL');
        $address = new Address($province, 'Pratteln', 'Hauptstrasse', '11');

        $school = new School('Global School');
        $school->setAddress($address);

        $serialized = $school->serialize();

        $this->assertTrue(is_string($serialized));

        $newSchool = new School('');
        $newSchool->unserialize($serialized);

        $this->assertTrue($newSchool instanceof School);
        $this->assertEquals(null, $newSchool->getId());
        $this->assertEquals('Global School', $newSchool->getName());
    }
}
