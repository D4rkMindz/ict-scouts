<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Address;
use AppBundle\Entity\Province;
use AppBundle\Entity\Workshop;
use AppBundle\Entity\Zip;
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

        $province = new Province('Baselland', 'BL');
        $zip = new Zip('4410', 'Liestal');
        $address = new Address($province, $zip, 'Hauptstrasse', '11');
        $entityManager->persist($province);
        $entityManager->persist($address);
        $entityManager->flush();

        $workshop = new Workshop('Great Workshop', $address);

        $this->assertNull($workshop->getId());
        $this->assertEquals('Great Workshop', $workshop->getName());
        $this->assertEquals($address, $workshop->getAddress());

        $entityManager->persist($workshop);
        $entityManager->flush();

        $this->assertNotNull($workshop->getId());

        $this->assertEquals('Great Workshop', $workshop->getName());
        $this->assertEquals($address, $workshop->getAddress());
    }

    /**
     * Tests serialization of the Workshop class.
     */
    public function testSerialization()
    {
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $province = new Province('Baselland', 'BL');
        $zip = new Zip('4410', 'Liestal');
        $address = new Address($province, $zip, 'Hauptstrasse', '11');
        $entityManager->persist($province);
        $entityManager->persist($address);
        $entityManager->flush();

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
