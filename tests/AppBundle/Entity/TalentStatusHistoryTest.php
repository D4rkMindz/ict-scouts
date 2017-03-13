<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Person;
use AppBundle\Entity\School;
use AppBundle\Entity\Talent;
use AppBundle\Entity\TalentStatus;
use AppBundle\Entity\TalentStatusHistory;
use AppBundle\Entity\User;
use Tests\AppBundle\KernelTest;

/**
 * Class TalentStatusHistoryTest.
 *
 * @covers \AppBundle\Entity\TalentStatusHistory
 */
class TalentStatusHistoryTest extends KernelTest
{
    /**
     * Tests getters and setters of TalentStatusHistory class.
     */
    public function testGetterAndSetter()
    {
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $group = $entityManager->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_TALENT']);

        $school = new School('Global School');

        $entityManager->persist($school);

        $person = new Person('Doe', 'John', 'Address');
        $person->setPhone('+41 79 123 45 67');
        $person->setMail('john.doe@example.com');

        $entityManager->persist($person);

        $user = new User('123456789', 'john.doe@example.com', 'abc123cba');
        $user->addGroup($group);

        $entityManager->persist($user);
        $entityManager->flush();

        $talent = new Talent($person, $user);
        $talent->setSchool($school);
        $talent->setVeggie(true);

        $talentStatus = $entityManager->getRepository('AppBundle:TalentStatus')->find(TalentStatus::ACTIVE);

        $entityManager->persist($talentStatus);
        $entityManager->flush();

        $talentStatusHistory = new TalentStatusHistory($talent, $talentStatus);

        $this->assertNull($talentStatusHistory->getId());
        $this->assertLessThanOrEqual((new \DateTime())->getTimestamp(), $talentStatusHistory->getChangeDate()->getTimestamp());
        $this->assertEquals($talent, $talentStatusHistory->getTalent());
        $this->assertEquals($talentStatus, $talentStatusHistory->getStatus());

        $entityManager->persist($talentStatusHistory);
        $entityManager->flush();

        $this->assertNotNull($talentStatusHistory->getId());
    }
}
