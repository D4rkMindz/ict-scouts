<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Person;
use AppBundle\Entity\Zip;
use Tests\AppBundle\KernelTest;

/**
 * Class PersonTest.
 */
class PersonTest extends KernelTest
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

	    $person = new Person();
	    $person->setGivenName('John');
	    $person->setFamilyName('Doe');
	    $person->setAddress('Test Street 101');
	    $person->setZip($zip);
	    $person->setPhone('+41 79 123 45 67');
	    $person->setMail('john.doe@example.com');
	    $birthDate = new \DateTime();
	    $person->setBirthDate($birthDate);

	    $this->assertNull($person->getId());
	    $this->assertEquals('John', $person->getGivenName());
	    $this->assertEquals('Doe', $person->getFamilyName());
	    $this->assertEquals('Test Street 101', $person->getAddress());
	    $this->assertEquals($zip->getZip(), $person->getZip()->getZip());
	    $this->assertEquals('+41 79 123 45 67', $person->getPhone());
	    $this->assertEquals('john.doe@example.com', $person->getMail());
	    $this->assertEquals($birthDate, $person->getBirthDate());

	    $em->persist($person);
	    $em->flush();

	    $this->assertEquals(1, $person->getId());
    }

    /**
     * Tests serialization of the Group class.
     */
    public function testSerialization()
    {
	    $zip = new Zip('0101', 'TestCity');

	    $person = new Person();
	    $person->setGivenName('John');
	    $person->setFamilyName('Doe');
	    $person->setAddress('Test Street 101');
	    $person->setZip($zip);
	    $person->setPhone('+41 79 123 45 67');
	    $person->setMail('john.doe@example.com');
	    $birthDate = new \DateTime();
	    $person->setBirthDate($birthDate);

	    $serialized = $person->serialize();

	    $this->assertTrue(is_string($serialized));

	    $newPerson = new Person();
	    $newPerson->unserialize($serialized);

	    $this->assertTrue($newPerson instanceof Person);
	    $this->assertEquals(null, $person->getId());
	    $this->assertEquals('Doe', $person->getFamilyName());
	    $this->assertEquals($zip->getZip(), $person->getZip()->getZip());
    }
}
