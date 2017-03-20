<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Province;
use Tests\AppBundle\KernelTest;

/**
 * Class ProvinceTest.
 *
 * @covers \AppBundle\Entity\Province
 */
class ProvinceTest extends KernelTest
{
    public function testName()
    {
        $province = new Province('', '');

        $province->setName('Basel-Landschaft');

        $this->assertEquals('Basel-Landschaft', $province->getName());
    }

    public function testNameShort()
    {
        $province = new Province('', '');

        $province->setNameShort('BL');

        $this->assertEquals('BL', $province->getNameShort());
    }

    public function testId()
    {
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $province = new Province('Basel-Stadt', 'BS');

        $this->assertNull($province->getId());

        $entityManager->persist($province);
        $entityManager->flush();

        $this->assertNotNull($province->getId());
    }
}
