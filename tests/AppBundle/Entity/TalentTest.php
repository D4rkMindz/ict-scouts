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

        $school = new School();
        $school->setName('Global School');
        $school->setAddress('School Street 42');
        $school->setAddress2('Building 23');
        $school->setZip($zip);

        $em->persist($school);

        $person = new Person();
        $person->setGivenName('John');
        $person->setFamilyName('Doe');
        $person->setAddress('Test Street 101');
        $person->setZip($zip);
        $person->setPhone('+41 79 123 45 67');
        $person->setMail('john.doe@example.com');
        $birthDate = new \DateTime();
        $person->setBirthDate($birthDate);

        $em->persist($person);

        $user = new User();
        $user->setGoogleId(123456789);
        $user->setEmail('john.doe@example.com');
        $user->setAccessToken('abc123cba');
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
        $this->assertTrue($talent->getVeggie());
    }
}