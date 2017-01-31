<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Module;
use AppBundle\Entity\Person;
use AppBundle\Entity\Scout;
use AppBundle\Entity\User;
use AppBundle\Entity\Zip;
use Tests\AppBundle\KernelTest;

/**
 * Class ModuleTest.
 */
class ModuleTest extends KernelTest
{
    /**
     * Tests getters and setters of Module class.
     */
    public function testGetterAndSetter()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $group = $em->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_SCOUT']);

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

        $person2 = new Person();
        $person2->setGivenName('Jane');
        $person2->setFamilyName('Doe');
        $person2->setAddress('Test Street 101');
        $person2->setZip($zip);
        $person2->setPhone('+41 79 123 45 67');
        $person2->setMail('jane.doe@example.com');
        $birthDate2 = new \DateTime();
        $person2->setBirthDate($birthDate2);

        $em->persist($person);
        $em->persist($person2);
        $em->flush();

        $user = new User();
        $user->setGoogleId(123456789);
        $user->setEmail('john.doe@example.com');
        $user->setAccessToken('abc123cba');
        $tokenExpireDate = (new \DateTime())->add(new \DateInterval('PT3595S'));
        $user->setAccessTokenExpireDate($tokenExpireDate);
        $user->addGroup($group);

        $user2 = new User();
        $user2->setGoogleId(987654321);
        $user2->setEmail('jane.doe@example.com');
        $user2->setAccessToken('cba123abc');
        $tokenExpireDate2 = (new \DateTime())->add(new \DateInterval('PT3595S'));
        $user2->setAccessTokenExpireDate($tokenExpireDate2);
        $user2->addGroup($group);

        $em->persist($user);
        $em->persist($user2);
        $em->flush();

        $scout = new Scout($person, $user);
        $scout2 = new Scout($person2, $user2);

        $module = new Module('Module 1');
        $module->setScouts([$scout]);
        $module->addScout($scout2);

        $this->assertNull($module->getId());
        $this->assertEquals('Module 1', $module->getName());
        $this->assertCount(2, $module->getScouts());

        $em->persist($module);
        $em->flush();

        $this->assertNotNull($module->getId());
    }

    /**
     * Tests serialization of the Module class.
     */
    public function testSerialization()
    {
        $module = new Module('Module 2');
        $serialized = $module->serialize();

        $this->assertTrue(is_string($serialized));

        $module1 = new Module('Module 1');
        $module1->unserialize($serialized);

        $this->assertNull($module1->getId());
        $this->assertEquals('Module 2', $module1->getName());
    }
}
