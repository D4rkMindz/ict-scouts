<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Person;
use AppBundle\Entity\School;
use AppBundle\Entity\Zip;
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

        $zip = new Zip('0101', 'TestCity');
        $em->persist($zip);
        $em->flush();

        $school = new School();
        $school->setName('Global School');
        $school->setAddress('Test Street 101');
        $school->setAddress2('Main Building');
        $school->setZip($zip);

        $this->assertNull($school->getId());
        $this->assertEquals('Global School', $school->getName());
        $this->assertEquals('Test Street 101', $school->getAddress());
        $this->assertEquals('Main Building', $school->getAddress2());
        $this->assertEquals($zip->getZip(), $school->getZip()->getZip());

        $em->persist($school);
        $em->flush();

        $this->assertEquals(1, $school->getId());
    }

    /**
     * Tests serialization of the Group class.
     */
    public function testSerialization()
    {
        $zip = new Zip('0101', 'TestCity');

        $school = new School();
        $school->setName('Global School');
        $school->setAddress('Test Street 101');
        $school->setAddress2('Main Building');
        $school->setZip($zip);

        $serialized = $school->serialize();

        $this->assertTrue(is_string($serialized));

        $newSchool = new School();
        $newSchool->unserialize($serialized);

        $this->assertTrue($newSchool instanceof School);
        $this->assertEquals(null, $newSchool->getId());
        $this->assertEquals('Global School', $newSchool->getName());
        $this->assertEquals($zip->getZip(), $school->getZip()->getZip());
    }
}
