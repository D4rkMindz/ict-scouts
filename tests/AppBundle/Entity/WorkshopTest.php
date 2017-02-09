<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Address;
use AppBundle\Entity\Province;
use AppBundle\Entity\Workshop;
use Tests\AppBundle\KernelTest;

/**
 * Class WorkshopTest.
 */
class WorkshopTest extends KernelTest
{
    /**
     * Tests getters and setters of Workshop class.
     */
    public function testGetterAndSetter()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $province = new Province('Baselland', 'BL');
        $address = new Address($province, 'Liestal', 'Hauptstrasse', '11');
        $em->persist($province);
        $em->persist($address);
        $em->flush();

        $workshop = new Workshop('Great Workshop', $address);

        $this->assertNull($workshop->getId());
        $this->assertEquals('Great Workshop', $workshop->getName());
        $this->assertEquals($address, $workshop->getAddress());

        $em->persist($workshop);
        $em->flush();

        $this->assertNotNull($workshop->getId());

        $this->assertEquals('Great Workshop', $workshop->getName());
        $this->assertEquals($address, $workshop->getAddress());
    }

    /**
     * Tests serialization of the Workshop class.
     */
    public function testSerialization()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $province = new Province('Baselland', 'BL');
        $address = new Address($province, 'Liestal', 'Hauptstrasse', '11');
        $em->persist($province);
        $em->persist($address);
        $em->flush();

        $workshop = new Workshop('Great Workshop', $address);
        $serialized = $workshop->serialize();

        $this->assertTrue(is_string($serialized));

        $workshop1 = new Workshop('Greatest Workshop', $address);
        $workshop1->unserialize($serialized);

        $this->assertNull($workshop1->getId());
        $this->assertEquals('Great Workshop', $workshop1->getName());
        $this->assertEquals($address, $workshop1->getAddress());
    }
}
