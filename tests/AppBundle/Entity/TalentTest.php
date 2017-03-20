<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Person;
use AppBundle\Entity\School;
use AppBundle\Entity\Talent;
use AppBundle\Entity\TalentStatusHistory;
use AppBundle\Entity\User;
use AppBundle\Entity\Zip;
use Tests\AppBundle\KernelTest;

/**
 * Class TalentTest.
 *
 * @covers \AppBundle\Entity\Talent
 */
class TalentTest extends KernelTest
{
    public function testSchool()
    {
        $talent = $this->createTalent();
        $school = new School('Fancy Test School');

        $talent->setSchool($school);

        $this->assertEquals($school, $talent->getSchool());
    }

    public function testVeggie()
    {
        $talent = $this->createTalent();
        $talent->setVeggie(true);

        $this->assertTrue($talent->isVeggie());
    }

    public function testTalentStatusHiostory()
    {
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $talent = $this->createTalent();

        $talentStatusHistory = new TalentStatusHistory($talent, Talent::ACTIVE);

        $entityManager->persist($talentStatusHistory);

        $talent->addTalentStatusHistory($talentStatusHistory);

        $this->assertCount(1, $talent->getTalentStatusHistory());
    }

    private function createTalent()
    {
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $school = new School('Global School');
        $person = new Person('Doe', 'John');

        $entityManager->persist($school);
        $entityManager->persist($person);

        $talent = new Talent($person);

        $this->assertNull($talent->getId());
        $this->assertEquals($person, $talent->getPerson());

        $entityManager->persist($talent);
        $entityManager->flush();

        $this->assertEquals(1, $talent->getId());

        return $talent;
    }
}
