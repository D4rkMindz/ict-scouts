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

        $person = new Person('Doe', 'John');
        $person->setPhone('+41 79 123 45 67');
        $person->setMail('john.doe@example.com');
        $entityManager->persist($person);

        $user = new User($person, '123456789', 'john.doe@example.com', 'abc123cba');
        $user->addGroup($group);

        $entityManager->persist($user);
        $entityManager->flush();

        $talent = new Talent($person, $school);
        $talent->setVeggie(true);

        $talentStatusHistory = new TalentStatusHistory($talent, Talent::ACTIVE);

        $this->assertNull($talentStatusHistory->getId());
        $this->assertLessThanOrEqual((new \DateTime())->getTimestamp(), $talentStatusHistory->getChangeDate()->getTimestamp());
        $this->assertEquals($talent, $talentStatusHistory->getTalent());
        $this->assertEquals(Talent::ACTIVE, $talentStatusHistory->getStatus());

        $entityManager->persist($talentStatusHistory);
        $entityManager->flush();

        $this->assertNotNull($talentStatusHistory->getId());
    }
}
