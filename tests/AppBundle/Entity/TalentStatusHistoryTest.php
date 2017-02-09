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
 */
class TalentStatusHistoryTest extends KernelTest
{
    /**
     * Tests getters and setters of TalentStatusHistory class.
     */
    public function testGetterAndSetter()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $group = $em->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_TALENT']);

        $school = new School('Global School');

        $em->persist($school);

        $person = new Person('Doe', 'John', 'Address');
        $person->setPhone('+41 79 123 45 67');
        $person->setMail('john.doe@example.com');

        $em->persist($person);

        $user = new User('123456789', 'john.doe@example.com', 'abc123cba');
        $user->addGroup($group);

        $em->persist($user);
        $em->flush();

        $talent = new Talent($person, $user);
        $talent->setSchool($school);
        $talent->setVeggie(true);

        $talentStatus = $em->getRepository('AppBundle:TalentStatus')->find(TalentStatus::ACTIVE);

        $em->persist($talentStatus);
        $em->flush();

        $talentStatusHistory = new TalentStatusHistory($talent, $talentStatus);

        $this->assertNull($talentStatusHistory->getId());
        $this->assertLessThanOrEqual((new \DateTime())->getTimestamp(), $talentStatusHistory->getChangeDate()->getTimestamp());
        $this->assertEquals($talent, $talentStatusHistory->getTalent());
        $this->assertEquals($talentStatus, $talentStatusHistory->getStatus());

        $em->persist($talentStatusHistory);
        $em->flush();

        $this->assertNotNull($talentStatusHistory->getId());
    }
}
