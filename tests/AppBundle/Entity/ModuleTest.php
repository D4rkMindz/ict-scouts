<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Module;
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
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $group = $em->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_SCOUT']);

        $zip = new Zip('0101', 'TestCity');
        $em->persist($zip);
        $em->flush();

        $user = new User('123456789', 'john.doe@example.com', 'abc123cba');
        $tokenExpireDate = (new \DateTime())->add(new \DateInterval('PT3595S'));
        $user->setAccessTokenExpireDate($tokenExpireDate);
        $user->addGroup($group);

        $user2 = new User('987654321', 'jane.doe@example.com', 'cba123abc');
        $tokenExpireDate2 = (new \DateTime())->add(new \DateInterval('PT3595S'));
        $user2->setAccessTokenExpireDate($tokenExpireDate2);
        $user2->addGroup($group);

        $em->persist($user);
        $em->persist($user2);
        $em->flush();

        $scout = new Scout($user);
        $scout2 = new Scout($user2);

        $module = new Module('Module 1');
        $module->addScout($scout);
        $module->addScout($scout2);

        $this->assertNull($module->getId());
        $this->assertEquals('Module 1', $module->getName());
        $this->assertCount(2, $module->getScouts());

        $em->persist($module);
        $em->flush();

        $module->setName('Module 1.1');
        $module->removeScout($scout2);

        $this->assertEquals('Module 1.1', $module->getName());
        $this->assertCount(1, $module->getScouts());
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
