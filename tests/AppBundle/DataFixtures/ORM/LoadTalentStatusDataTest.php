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
        $aargau = $repo->findOneBy(['nameShort' => 'AG']);
        $baselLandschaft = $repo->findOneBy(['nameShort' => 'BL']);
        $zurich = $repo->findOneBy(['nameShort' => 'ZH']);
        $all = $repo->findAll();

        $this->assertEquals('Aargau', $aargau->getName());
        $this->assertEquals('Basel-Landschaft', $baselLandschaft->getName());
        $this->assertEquals('Zürich', $zurich->getName());
        $this->assertCount(26, $all);

    }
}
