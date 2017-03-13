<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Zip;
use Tests\AppBundle\KernelTest;

/**
 * Class ZipTest.
 *
 * @covers \AppBundle\Entity\Zip
 */
class ZipTest extends KernelTest
{
    /**
     * Tests getters and setters of Zip class.
     */
    public function testGetterAndSetter()
    {
        $zip = new Zip('0101', 'TestCity');

        $this->assertNull($zip->getId());
        $this->assertEquals('0101', $zip->getZip());
        $this->assertEquals('TestCity', $zip->getCity());

        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $entityManager->persist($zip);
        $entityManager->flush();

        $zip = $entityManager->getRepository('AppBundle:Zip')->findOneBy(['zip' => '0101']);

        $this->assertNotNull($zip->getId());
    }

    /**
     * Tests serialization of the Zip class.
     */
    public function testSerialization()
    {
        $zip = new Zip('0101', 'TestCity');
        $serialized = $zip->serialize();

        $this->assertTrue(is_string($serialized));

        $zip1 = new Zip('9999', 'MyCity');
        $zip1->unserialize($serialized);

        $this->assertNull($zip1->getId());
        $this->assertEquals('0101', $zip1->getZip());
        $this->assertEquals('TestCity', $zip1->getCity());
    }
}
