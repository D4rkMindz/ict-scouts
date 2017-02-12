<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Person;
use AppBundle\Entity\Zip;
use Tests\AppBundle\KernelTest;

/**
 * Class PersonTest.
 *
 * @covers \AppBundle\Entity\Person
 *
 * @TODO: Rewrite test after rewriting Person-Entity.
 */
class PersonTest extends KernelTest
{
    /**
     * Tests getters and setters of Person class.
     */
    public function testGetterAndSetter()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $person = new Person('', '', 'Test Street 101');
        $person->setGivenName('John');
        $person->setFamilyName('Doe');
        $person->setPhone('+41 79 123 45 67');
        $person->setMail('john.doe@example.com');
        $birthDate = new \DateTime();
        $person->setBirthDate($birthDate);

        $this->assertNull($person->getId());
        $this->assertEquals('John', $person->getGivenName());
        $this->assertEquals('Doe', $person->getFamilyName());
        $this->assertEquals('Test Street 101', $person->getAddress());
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
        $person = new Person('', '', '');
        $person->setGivenName('John');
        $person->setFamilyName('Doe');
        $person->setPhone('+41 79 123 45 67');
        $person->setMail('john.doe@example.com');
        $birthDate = new \DateTime();
        $person->setBirthDate($birthDate);

        $serialized = $person->serialize();

        $this->assertTrue(is_string($serialized));

        $newPerson = new Person('', '', '');
        $newPerson->unserialize($serialized);

        $this->assertTrue($newPerson instanceof Person);
        $this->assertEquals(null, $newPerson->getId());
        $this->assertEquals('Doe', $newPerson->getFamilyName());
    }
}
