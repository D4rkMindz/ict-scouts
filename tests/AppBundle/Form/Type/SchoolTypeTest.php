<?php

namespace Tests\AppBundle\Form\Type;

use AppBundle\Entity\School;
use AppBundle\Form\Type\SchoolType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class SchoolTypeTest.
 *
 * @covers \AppBundle\Form\Type\ModuleType
 */
class SchoolTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $module = new School('');

        $formData = [
            'name' => 'test-school',
        ];

        $this->assertEquals('', $module->getName());

        $form = $this->factory->create(SchoolType::class, $module);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertEquals('test-school', $module->getName());

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($module, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
