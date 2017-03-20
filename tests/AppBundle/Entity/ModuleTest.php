<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Module;
use AppBundle\Entity\ModulePart;
use AppBundle\Entity\Person;
use AppBundle\Entity\Scout;
use AppBundle\Entity\User;
use AppBundle\Entity\Zip;
use Tests\AppBundle\KernelTest;

/**
 * Class ModuleTest.
 *
 * @covers \AppBundle\Entity\Module
 */
class ModuleTest extends KernelTest
{
    /**
     * Tests getters and setters of Module class.
     */
    public function testGetterAndSetter()
    {
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $group = $entityManager->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_SCOUT']);

        $person = new Person('Doe', 'John');
        $person2 = new Person('Doe', 'Jane');
        $entityManager->persist($person);
        $entityManager->persist($person2);

        $zip = new Zip('0101', 'TestCity');
        $entityManager->persist($zip);

        $user = new User($person, '123456789', 'john.doe@example.com', 'abc123cba');
        $tokenExpireDate = (new \DateTime())->add(new \DateInterval('PT3595S'));
        $user->setAccessTokenExpireDate($tokenExpireDate);
        $user->addGroup($group);

        $user2 = new User($person2, '987654321', 'jane.doe@example.com', 'cba123abc');
        $tokenExpireDate2 = (new \DateTime())->add(new \DateInterval('PT3595S'));
        $user2->setAccessTokenExpireDate($tokenExpireDate2);
        $user2->addGroup($group);

        $entityManager->persist($user);
        $entityManager->persist($user2);
        $entityManager->flush();

        $scout = new Scout($person);
        $scout2 = new Scout($person2);

        $module = new Module();
        $module->setName('Module 1');
        $module->addScout($scout);
        $module->addScout($scout2);

        $this->assertNull($module->getId());
        $this->assertEquals('Module 1', $module->getName());
        $this->assertCount(2, $module->getScouts());

        $entityManager->persist($module);
        $entityManager->flush();

        $modulePart = new ModulePart();
        $modulePart->setName('Test-Module-Part-1');
        $modulePart->setModule($module);

        $entityManager->persist($modulePart);
        $entityManager->flush();

        $module->addModulePart($modulePart);

        $this->assertCount(1, $module->getModuleParts());

        $module->setName('Module 1.1');
        $module->removeScout($scout2);
        $module->removeModulePart($modulePart);

        $this->assertNotNull($module->getId());
        $this->assertEquals('Module 1.1', $module->getName());
        $this->assertCount(1, $module->getScouts());
        $this->assertEmpty($module->getModuleParts());
    }

    /**
     * Tests serialization of the Module class.
     */
    public function testSerialization()
    {
        $module = new Module();
        $module->setName('Module 2');
        $serialized = $module->serialize();

        $this->assertTrue(is_string($serialized));

        $module1 = new Module();
        $module1->setName('Module 1');
        $module1->unserialize($serialized);

        $this->assertNull($module1->getId());
        $this->assertEquals('Module 2', $module1->getName());
    }
}
