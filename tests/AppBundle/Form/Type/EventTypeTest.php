<?php

namespace Tests\AppBundle\Form\Type;

use AppBundle\Entity\Event;
use AppBundle\Form\Type\EventType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class EventTypeTest.
 *
 * @covers \AppBundle\Form\Type\EventType
 */
class EventTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $event = new Event();

        $startDate = new \DateTime();
        $endDate = (new \DateTime())->add(new \DateInterval('P1D'));

        $formData = [
            'name'      => 'test-event',
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ];

        $this->assertNull($event->getName());

        $form = $this->factory->create(EventType::class, $event);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertEquals('test-event', $event->getName());
        $this->assertEquals($startDate, $event->getStartDate());
        $this->assertEquals($endDate, $event->getEndDate());

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($event, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
