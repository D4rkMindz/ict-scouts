<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Cast;
use Tests\AppBundle\KernelTest;

/**
 * Class CastTest.
 */
class CastTest extends KernelTest
{
    /**
     * Tests getters and setters of Cast class.
     */
    public function testGetterAndSetter()
    {
        $cast = new Cast('https://hangouts.google.com');

        $this->assertNull($cast->getId());
        $this->assertEquals('https://hangouts.google.com', $cast->getUrl());

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $em->persist($cast);
        $em->flush();

        $this->assertNotNull($cast->getId());
    }

    /**
     * Tests serialization of the Cast class.
     */
    public function testSerialization()
    {
        $cast = new Cast('https://www.google.com');
        $serialized = $cast->serialize();

        $this->assertTrue(is_string($serialized));

        $cast1 = new Cast('https://hangouts.google.com');
        $cast1->unserialize($serialized);

        $this->assertNull($cast1->getId());
        $this->assertEquals('https://www.google.com', $cast1->getUrl());
    }
}
