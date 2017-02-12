<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Address;
use AppBundle\Entity\Camp;
use AppBundle\Entity\Province;
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
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $province = new Province('Baselland', 'BL');
        $address = new Address($province, 'Liestal', 'Hauptstrasse', '11');
        $address2 = new Address($province, 'Liestal', 'Hauptstrasse', '12');

        $em->persist($province);
        $em->persist($address);
        $em->flush();

        $camp = new Camp('Great Camp', $address);

        $this->assertNull($camp->getId());
        $this->assertEquals('Great Camp', $camp->getName());
        $this->assertEquals($address, $camp->getAddress());

        $em->persist($camp);
        $em->flush();

        $camp->setAddress($address2);

        $this->assertEquals($address2, $camp->getAddress());
        $this->assertNotNull($camp->getId());
    }

    /**
     * Tests serialization of the Camp class.
     */
    public function testSerialization()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $province = new Province('Baselland', 'BL');
        $address = new Address($province, 'Liestal', 'Hauptstrasse', '11');

        $em->persist($province);
        $em->persist($address);
        $em->flush();

        $province1 = new Province('', '');
        $address1 = new Address($province1, '', '', '');

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
