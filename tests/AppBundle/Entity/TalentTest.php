<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Person;
use AppBundle\Entity\School;
use AppBundle\Entity\Talent;
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
    /**
     * Tests getters and setters of Talent class.
     */
    public function testGetterAndSetter()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $group = $em->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_TALENT']);

        $zip = new Zip('0101', 'TestCity');
        $em->persist($zip);
        $em->flush();

        $school = new School('Global School');

        $em->persist($school);

        $person = new Person('Doe', 'John', 'Address');
        $person->setPhone('+41 79 123 45 67');
        $person->setMail('john.doe@example.com');

        $em->persist($person);

        $user = new User('123456789', 'john.doe@example.com', 'abc123cba');
        $tokenExpireDate = (new \DateTime())->add(new \DateInterval('PT3595S'));
        $user->setAccessTokenExpireDate($tokenExpireDate);
        $user->addGroup($group);

        $em->persist($user);
        $em->flush();

        $talent = new Talent($person, $user);
        $talent->setSchool($school);
        $talent->setVeggie(true);

        $this->assertEquals($person->getFamilyName(), $talent->getPerson()->getFamilyName());
        $this->assertEquals($user->getEmail(), $talent->getUser()->getEmail());
        $this->assertEquals($school->getName(), $talent->getSchool()->getName());
        $this->assertTrue($talent->isVeggie());
    }
}
