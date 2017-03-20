<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Address;
use AppBundle\Entity\Camp;
use AppBundle\Entity\Province;
use AppBundle\Entity\Zip;
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

        $province = new Province('Baselland', 'BL');
        $zip = new Zip('4410', 'Liestal');
        $address = new Address('Hauptstrasse 11', $zip, $province);
        $address2 = new Address('Hauptstrasse 12', $zip, $province);

        $entityManager->persist($province);
        $entityManager->persist($address);
        $entityManager->flush();

        $camp = new Camp('Great Camp', $address);

        $this->assertNull($camp->getId());
        $this->assertEquals('Great Camp', $camp->getName());
        $this->assertEquals($address, $camp->getAddress());

        $entityManager->persist($camp);
        $entityManager->flush();

        $camp->setName('Greatest Camp');
        $camp->setAddress($address2);

        $this->assertEquals('Greatest Camp', $camp->getName());
        $this->assertEquals($address2, $camp->getAddress());
        $this->assertNotNull($camp->getId());
    }

    /**
     * Tests serialization of the Camp class.
     */
    public function testSerialization()
    {
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $province = new Province('Baselland', 'BL');
        $zip = new Zip('4410', 'Liestal');
        $address = new Address('Hauptstrasse 11', $zip, $province);

        $entityManager->persist($province);
        $entityManager->persist($address);
        $entityManager->flush();

        $province1 = new Province('', '');
        $zip1 = new Zip('', '');
        $address1 = new Address('', $zip1, $province1);

        $camp = new Camp('Great Camp', $address);
        $serialized = $camp->serialize();

        $this->assertTrue(is_string($serialized));

        $camp1 = new Camp('', $address1);
        $camp1->unserialize($serialized);

        $this->assertNull($camp1->getId());
        $this->assertEquals('Great Camp', $camp1->getName());
        $this->assertEquals($address, $camp1->getAddress());
    }
}
