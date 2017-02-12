<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Address;
use AppBundle\Entity\Camp;
use AppBundle\Entity\Cast;
use AppBundle\Entity\Module;
use AppBundle\Entity\ModulePart;
use AppBundle\Entity\Province;
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
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $province = new Province('Baselland', 'BL');
        $address = new Address($province, 'Pratteln', 'Hauptstrasse', '11');
        $address1 = new Address($province, 'Pratteln', 'Hauptstrasse', '11');
        $module = new Module('Module 1');
        $workshop = new Workshop('Great Workshop', $address1);
        $camp = new Camp('Great Camp', $address);
        $cast = new Cast('https://www.google.com');

        $em->persist($address);
        $em->persist($address1);
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

        $modulePart->setName('Module 1 - Part 1.1');

        $this->assertEquals('Module 1 - Part 1.1', $modulePart->getName());
        $this->assertNotNull($modulePart->getId());
    }
}
