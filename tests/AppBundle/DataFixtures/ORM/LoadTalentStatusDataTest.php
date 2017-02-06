<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\TalentStatus;
use Tests\AppBundle\KernelTest;

/**
 * Class LoadTalentStatusDataTest.
 */
class LoadTalentStatusDataTest extends KernelTest
{
    /**
     * Tests load function.
     */
    public function testLoad()
    {
        $repo = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:TalentStatus');
        $active = $repo->findOneBy(['name' => 'Aktiv']);
        $inactive = $repo->findOneBy(['name' => 'Inaktiv']);
        $former = $repo->findOneBy(['name' => 'Ehemalig']);

        $this->assertEquals($active->getId(), TalentStatus::ACTIVE);
        $this->assertEquals($inactive->getId(), TalentStatus::INACTIVE);
        $this->assertEquals($former->getId(), TalentStatus::FORMER);
    }
}
