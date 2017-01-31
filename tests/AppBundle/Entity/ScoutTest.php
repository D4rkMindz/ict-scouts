<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Module;
use AppBundle\Entity\Person;
use AppBundle\Entity\Scout;
use AppBundle\Entity\User;
use AppBundle\Entity\Zip;
use Tests\AppBundle\KernelTest;

/**
 * Class ScoutTest.
 */
class ScoutTest extends KernelTest
{
    /**
     * Tests getters and setters of Scout class.
     */
    public function testGetterAndSetter()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $group = $em->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_SCOUT']);

        $zip = new Zip('0101', 'TestCity');
        $module = new Module('Module 1');
        $module2 = new Module('Module 2');
        $em->persist($zip);
        $em->flush();

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
        $em->flush();

        $user = new User();
        $user->setGoogleId(123456789);
        $user->setEmail('john.doe@example.com');
        $user->setAccessToken('abc123cba');
        $tokenExpireDate = (new \DateTime())->add(new \DateInterval('PT3595S'));
        $user->setAccessTokenExpireDate($tokenExpireDate);
        $user->addGroup($group);

        $em->persist($user);
        $em->flush();

        $scout = new Scout($person, $user);
        $scout->setModules([$module]);
        $scout->addModule($module2);

        $this->assertEquals($person->getFamilyName(), $scout->getPerson()->getFamilyName());
        $this->assertEquals($user->getEmail(), $scout->getUser()->getEmail());
        $this->assertCount(2, $scout->getModules());
    }
}
