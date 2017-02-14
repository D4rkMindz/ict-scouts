<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Event;
use Tests\AppBundle\KernelTest;

/**
 * Class EventTest.
 *
 * @covers \AppBundle\Entity\Event
 */
class EventTest extends KernelTest
{
    /**
     * Tests getters and setters of Person class.
     */
    public function testGetterAndSetter()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $event = new Event();
        $event->setName('Automated-Test-Event');

        $this->assertNull($event->getId());
        $this->assertEquals('Automated-Test-Event', $event->getName());
        $this->assertNotNull($event->getStartDate());
        $this->assertNotNull($event->getEndDate());

        $em->persist($event);
        $em->flush();

        $this->assertNotNull($event->getId());

        $startDate = new \DateTime();
        $event->setStartDate($startDate);
        $endDate = new \DateTime();
        $event->setEndDate($endDate);

        $this->assertEquals($startDate, $event->getStartDate());
        $this->assertEquals($endDate, $event->getEndDate());
    }
}
