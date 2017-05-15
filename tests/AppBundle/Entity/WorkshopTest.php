<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Workshop;
use Tests\AppBundle\KernelTest;

/**
 * Class WorkshopTest.
 *
 * @covers \AppBundle\Entity\Workshop
 */
class WorkshopTest extends KernelTest
{
    /**
     * Tests getters and setters of Workshop class.
     */
    public function testGetterAndSetter()
    {
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $workshop = new Workshop('Great Workshop');

        $this->assertNull($workshop->getId());
        $this->assertEquals('Great Workshop', $workshop->getName());

        $entityManager->persist($workshop);
        $entityManager->flush();

        $this->assertNotNull($workshop->getId());

        $this->assertEquals('Great Workshop', $workshop->getName());
    }

    /**
     * Tests serialization of the Workshop class.
     */
    public function testSerialization()
    {
        $workshop = new Workshop('Great Workshop');
        $serialized = $workshop->serialize();

        $this->assertTrue(is_string($serialized));

        $workshop1 = new Workshop('Greatest Workshop');
        $workshop1->unserialize($serialized);

        $this->assertNull($workshop1->getId());
        $this->assertEquals('Great Workshop', $workshop1->getName());
    }
}
