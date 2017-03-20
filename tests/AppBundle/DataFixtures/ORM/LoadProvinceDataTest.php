<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Province;
use Tests\AppBundle\KernelTest;

/**
 * Class LoadProvinceDataTest.
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
        /** @var Province $aargau */
        $aargau = $repo->findOneBy(['nameShort' => 'AG']);
        /** @var Province $baselLandschaft */
        $baselLandschaft = $repo->findOneBy(['nameShort' => 'BL']);
        /** @var Province $zurich */
        $zurich = $repo->findOneBy(['nameShort' => 'ZH']);
        $all = $repo->findAll();

        $this->assertEquals('Aargau', $aargau->getName());
        $this->assertEquals('Basel-Landschaft', $baselLandschaft->getName());
        $this->assertEquals('Zürich', $zurich->getName());
        $this->assertCount(26, $all);
    }
}
