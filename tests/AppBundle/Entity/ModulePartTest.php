<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Camp;
use AppBundle\Entity\Cast;
use AppBundle\Entity\Module;
use AppBundle\Entity\ModulePart;
use AppBundle\Entity\Workshop;
use Tests\AppBundle\KernelTest;

/**
 * Class ModulePartTest.
 *
 * @covers \AppBundle\Entity\ModulePart
 */
class ModulePartTest extends KernelTest
{
    /**
     * Tests getters and setters of ModulePart class.
     */
    public function testGetterAndSetter()
    {
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $module = new Module();
        $module->setName('Module 1');
        $workshop = new Workshop('Great Workshop');
        $camp = new Camp('Great Camp');
        $cast = new Cast('https://hangouts.google.com');

        $entityManager->persist($workshop);
        $entityManager->persist($camp);
        $entityManager->persist($cast);
        $entityManager->flush();

        $modulePart = new ModulePart();
        $modulePart->setName('Module 1 - Part 1');
        $modulePart->setModule($module);
        $modulePart->setWorkshop($workshop);
        $modulePart->setCamp($camp);
        $modulePart->setCast($cast);

        $this->assertNull($modulePart->getId());
        $this->assertEquals('Module 1 - Part 1', $modulePart->getName());
        $this->assertEquals($module, $modulePart->getModule());
        $this->assertEquals($workshop, $modulePart->getWorkshop());
        $this->assertEquals($camp, $modulePart->getCamp());
        $this->assertEquals($cast, $modulePart->getCast());

        $entityManager->persist($modulePart);
        $entityManager->flush();

        $modulePart->setName('Module 1 - Part 1.1');

        $this->assertEquals('Module 1 - Part 1.1', $modulePart->getName());
        $this->assertNotNull($modulePart->getId());
    }
}
