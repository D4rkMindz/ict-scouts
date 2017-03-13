<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\TalentStatus;
use Tests\AppBundle\KernelTest;

/**
 * Class TalentStatusTest.
 *
 * @covers \AppBundle\Entity\TalentStatus
 */
class TalentStatusTest extends KernelTest
{
    /**
     * Tests getters and setters of TalentStatus class.
     */
    public function testGetterAndSetter()
    {
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $talentStatus = new TalentStatus('foo');

        $this->assertNull($talentStatus->getId());
        $this->assertEquals('foo', $talentStatus->getName());

        $entityManager->persist($talentStatus);
        $entityManager->flush();

        $this->assertNotNull($talentStatus->getId());
    }
}
