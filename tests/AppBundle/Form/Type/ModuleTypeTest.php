<?php

namespace Tests\AppBundle\Form\Type;

use AppBundle\Entity\Module;
use AppBundle\Form\Type\ModuleType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class ModuleTypeTest.
 *
 * @covers \AppBundle\Form\Type\ModuleType
 */
class ModuleTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $module = new Module('');

        $formData = [
            'name' => 'test',
        ];

        $this->assertEquals('', $module->getName());

        $form = $this->factory->create(ModuleType::class, $module);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertEquals('test', $module->getName());

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($module, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
