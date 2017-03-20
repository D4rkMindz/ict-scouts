<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Province;
use Tests\AppBundle\KernelTest;

/**
 * Class LoadTalentStatusDataTest.
 *
 * @covers \AppBundle\DataFixtures\ORM\LoadProvinceData
 */
class LoadProvinceDataTest extends KernelTest
{
    /**
     * Tests load function.
     */
    public function testLoad()
    {
        $repo = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:Province');
        $ag = $repo->findOneBy(['nameShort' => 'AG']);
        $bl = $repo->findOneBy(['nameShort' => 'BL']);
        $zh = $repo->findOneBy(['nameShort' => 'ZH']);
        $all = $repo->findAll();

        $this->assertEquals('Aargau', $ag->getName());
        $this->assertEquals('Basel-Landschaft', $bl->getName());
        $this->assertEquals('Zürich', $zh->getName());
        $this->assertCount(26, $all);

    }
}
