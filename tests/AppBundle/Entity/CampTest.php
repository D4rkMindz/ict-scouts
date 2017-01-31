<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Camp;
use AppBundle\Entity\Zip;
use Tests\AppBundle\KernelTest;

/**
 * Class CampTest.
 */
class CampTest extends KernelTest
{
    /**
     * Tests getters and setters of Camp class.
     */
    public function testGetterAndSetter()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $zip = new Zip('0101', 'TestCity');
        $zip2 = new Zip('0102', 'TestCity');

        $em->persist($zip);
        $em->persist($zip2);
        $em->flush();

        $camp = new Camp('Great Camp', 'Camp Street 1', $zip);
        $camp->setAddress2('Building 3');

        $this->assertNull($camp->getId());
        $this->assertEquals('Great Camp', $camp->getName());
        $this->assertEquals('Camp Street 1', $camp->getAddress());
        $this->assertEquals('Building 3', $camp->getAddress2());
        $this->assertEquals($zip, $camp->getZip());

        $em->persist($camp);
        $em->flush();

        $this->assertNotNull($camp->getId());

        $camp->setName('Greatest Camp');
        $camp->setAddress('Camp Street 5');
        $camp->setZip($zip2);

        $this->assertEquals('Greatest Camp', $camp->getName());
        $this->assertEquals('Camp Street 5', $camp->getAddress());
        $this->assertEquals($zip2, $camp->getZip());
    }

    /**
     * Tests serialization of the Camp class.
     */
    public function testSerialization()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $zip = new Zip('0101', 'TestCity');
        $zip2 = new Zip('0102', 'TestCity');

        $em->persist($zip);
        $em->persist($zip2);
        $em->flush();

        $camp = new Camp('Great Camp', 'Camp Street 1', $zip);
        $camp->setAddress2('Building 3');
        $serialized = $camp->serialize();

        $this->assertTrue(is_string($serialized));

        $camp1 = new Camp('Greatest Camp', 'Camp Street 5', $zip2);
        $camp1->unserialize($serialized);

        $this->assertNull($camp1->getId());
        $this->assertEquals('Great Camp', $camp1->getName());
        $this->assertEquals('Camp Street 1', $camp1->getAddress());
        $this->assertEquals($zip, $camp1->getZip());
    }
}
