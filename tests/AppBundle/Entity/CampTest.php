<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Camp;
use Tests\AppBundle\KernelTest;

/**
 * Class CampTest.
 *
 * @covers \AppBundle\Entity\Camp
 */
class CampTest extends KernelTest
{
    /**
     * Tests getters and setters of Camp class.
     */
    public function testGetterAndSetter()
    {
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $camp = new Camp('Great Camp');

        $this->assertNull($camp->getId());
        $this->assertEquals('Great Camp', $camp->getName());

        $entityManager->persist($camp);
        $entityManager->flush();

        $camp->setName('Greatest Camp');

        $this->assertEquals('Greatest Camp', $camp->getName());
        $this->assertNotNull($camp->getId());
    }

    /**
     * Tests serialization of the Camp class.
     */
    public function testSerialization()
    {
        $camp = new Camp('Great Camp');
        $serialized = $camp->serialize();

        $this->assertTrue(is_string($serialized));

        $camp1 = new Camp('');
        $camp1->unserialize($serialized);

        $this->assertNull($camp1->getId());
        $this->assertEquals('Great Camp', $camp1->getName());
    }
}
