<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Module;
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

        $module = new Module('Module 1');

        $this->assertNull($module->getId());
        $this->assertEquals('Module 1', $module->getName());

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
