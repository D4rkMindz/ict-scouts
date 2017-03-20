<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Address;
use AppBundle\Entity\Camp;
use AppBundle\Entity\Cast;
use AppBundle\Entity\Module;
use AppBundle\Entity\ModulePart;
use AppBundle\Entity\Province;
use AppBundle\Entity\Workshop;
use AppBundle\Entity\Zip;
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

        $province = new Province('Baselland', 'BL');
        $zip = new Zip('4133', 'Pratteln');
        $address = new Address('Hauptstrasse 11', $zip, $province);
        $address1 = new Address('Hauptstrasse 12', $zip, $province);
        $module = new Module();
        $module->setName('Module 1');
        $workshop = new Workshop('Great Workshop', $address1);
        $camp = new Camp('Great Camp', $address);
        $cast = new Cast('https://www.google.com');

        $entityManager->persist($address);
        $entityManager->persist($address1);
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
