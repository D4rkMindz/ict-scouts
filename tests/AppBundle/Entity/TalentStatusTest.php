<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\TalentStatus;
use Tests\AppBundle\KernelTest;

/**
 * Class TalentStatusTest.
 */
class TalentStatusTest extends KernelTest
{
    /**
     * Tests getters and setters of TalentStatus class.
     */
    public function testGetterAndSetter()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $talentStatus = new TalentStatus('Aktiv');

        $this->assertNull($talentStatus->getId());
        $this->assertEquals('Aktiv', $talentStatus->getName());

        $em->persist($talentStatus);
        $em->flush();

        $this->assertNotNull($talentStatus->getId());
    }
}