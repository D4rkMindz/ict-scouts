<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Camp;
use AppBundle\Entity\Cast;
use AppBundle\Entity\Module;
use AppBundle\Entity\ModulePart;
use AppBundle\Entity\Workshop;
use AppBundle\Entity\Zip;
use Tests\AppBundle\KernelTest;

/**
 * Class ModulePartTest.
 */
class ModulePartTest extends KernelTest
{
    /**
     * Tests getters and setters of ModulePart class.
     */
    public function testGetterAndSetter()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $zip = new Zip('0101', 'TestCity');
        $em->persist($zip);
        $em->flush();

        $module = new Module('Module 1');
        $workshop = new Workshop('Great Workshop', 'Workshop Street 1', $zip);
        $camp = new Camp('Great Camp', 'Camp Street 1', $zip);
        $cast = new Cast('https://www.google.com');

        $em->persist($workshop);
        $em->persist($camp);
        $em->persist($cast);
        $em->flush();

        $modulePart = new ModulePart('Module 1 - Part 1', $module);
        $modulePart->setWorkshop($workshop);
        $modulePart->setCamp($camp);
        $modulePart->setCast($cast);

        $this->assertNull($modulePart->getId());
        $this->assertEquals('Module 1 - Part 1', $modulePart->getName());
        $this->assertEquals($module, $modulePart->getModule());
        $this->assertEquals($workshop, $modulePart->getWorkshop());
        $this->assertEquals($camp, $modulePart->getCamp());
        $this->assertEquals($cast, $modulePart->getCast());

        $em->persist($modulePart);
        $em->flush();

        $this->assertNotNull($modulePart->getId());
    }
}
