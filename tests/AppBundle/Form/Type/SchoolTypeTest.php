<?php

namespace Tests\AppBundle\Form\Type;

use AppBundle\Entity\School;
use AppBundle\Form\Type\SchoolType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class SchoolTypeTest.
 *
 * @covers \AppBundle\Form\Type\SchoolType
 */
class SchoolTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $school = new School('');

        $formData = [
            'name' => 'test-school',
        ];

        $this->assertEquals('', $school->getName());

        $form = $this->factory->create(SchoolType::class, $school);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertEquals('test-school', $school->getName());

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($school, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
