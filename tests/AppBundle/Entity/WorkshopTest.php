<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Workshop;
use AppBundle\Entity\Zip;
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

        $zip = new Zip('0101', 'TestCity');
        $zip2 = new Zip('0102', 'TestCity');

        $em->persist($zip);
        $em->persist($zip2);
        $em->flush();

        $workshop = new Workshop('Great Workshop', 'Workshop Street 1', $zip);
        $workshop->setAddress2('Building 3');

        $this->assertNull($workshop->getId());
        $this->assertEquals('Great Workshop', $workshop->getName());
        $this->assertEquals('Workshop Street 1', $workshop->getAddress());
        $this->assertEquals('Building 3', $workshop->getAddress2());
        $this->assertEquals($zip, $workshop->getZip());

        $em->persist($workshop);
        $em->flush();

        $this->assertNotNull($workshop->getId());

        $workshop->setName('Greatest Workshop');
        $workshop->setAddress('Workshop Street 5');
        $workshop->setZip($zip2);

        $this->assertEquals('Greatest Workshop', $workshop->getName());
        $this->assertEquals('Workshop Street 5', $workshop->getAddress());
        $this->assertEquals($zip2, $workshop->getZip());
    }

    /**
     * Tests serialization of the Workshop class.
     */
    public function testSerialization()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $zip = new Zip('0101', 'TestCity');
        $zip2 = new Zip('0102', 'TestCity');

        $em->persist($zip);
        $em->persist($zip2);
        $em->flush();

        $workshop = new Workshop('Great Workshop', 'Workshop Street 1', $zip);
        $workshop->setAddress2('Building 3');
        $serialized = $workshop->serialize();

        $this->assertTrue(is_string($serialized));

        $workshop1 = new Workshop('Greatest Workshop', 'Workshop Street 5', $zip2);
        $workshop1->unserialize($serialized);

        $this->assertNull($workshop1->getId());
        $this->assertEquals('Great Workshop', $workshop1->getName());
        $this->assertEquals('Workshop Street 1', $workshop1->getAddress());
        $this->assertEquals($zip, $workshop1->getZip());
    }
}
