<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Address;
use AppBundle\Entity\Person;
use AppBundle\Entity\Province;
use AppBundle\Entity\Zip;
use Tests\AppBundle\KernelTest;

/**
 * Class PersonTest.
 *
 * @covers \AppBundle\Entity\Person
 */
class PersonTest extends KernelTest
{
    public function testGivenName()
    {
        $person = new Person('', '');

        $this->assertEmpty($person->getGivenName());

        $person->setGivenName('John');

        $this->assertEquals('John', $person->getGivenName());
    }

    public function testFamilyName()
    {
        $person = new Person('', '');

        $this->assertEmpty($person->getFamilyName());

        $person->setFamilyName('Doe');

        $this->assertEquals('Doe', $person->getFamilyName());
    }

    public function testAddress()
    {
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $province = new Province('Baselland', 'BL');
        $zip = new Zip('4410', 'Liestal');
        $address = new Address('Hauptstrasse 11', $zip, $province);

        $entityManager->persist($zip);
        $entityManager->persist($province);
        $entityManager->persist($address);
        $entityManager->flush();

        $person = new Person('', '');

        $this->assertNull($person->getAddress());

        $person->setAddress($address);

        $this->assertEquals($address, $person->getAddress());
    }

    public function testPhone()
    {
        $person = new Person('', '');

        $this->assertNull($person->getPhone());

        $person->setPhone('+41 79 123 45 67');

        $this->assertEquals('+41 79 123 45 67', $person->getPhone());
    }

    public function testMail()
    {
        $person = new Person('', '');

        $this->assertNull($person->getMail());

        $person->setMail('john.doe@example.com');

        $this->assertEquals('john.doe@example.com', $person->getMail());
    }

    public function testBirthdate()
    {
        $birthDate = new \DateTime();

        $person = new Person('', '');

        $this->assertNull($person->getBirthDate());

        $person->setBirthDate($birthDate);

        $this->assertEquals($birthDate, $person->getBirthDate());
    }

    /**
     * Tests getters and setters of Person class.
     */
    public function testId()
    {
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $person = new Person('', '');

        $this->assertNull($person->getId());

        $entityManager->persist($person);
        $entityManager->flush();

        $this->assertEquals(1, $person->getId());
    }

    /**
     * Tests serialization of the Group class.
     */
    public function testSerialization()
    {
        $person = new Person('', '');
        $person->setGivenName('John');
        $person->setFamilyName('Doe');
        $person->setPhone('+41 79 123 45 67');
        $person->setMail('john.doe@example.com');
        $birthDate = new \DateTime();
        $person->setBirthDate($birthDate);

        $serialized = $person->serialize();

        $this->assertTrue(is_string($serialized));

        $newPerson = new Person('', '');
        $newPerson->unserialize($serialized);

        $this->assertInstanceOf(Person::class, $newPerson);
        $this->assertEquals(null, $newPerson->getId());
        $this->assertEquals('Doe', $newPerson->getFamilyName());
    }
}
