<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Module;
use AppBundle\Entity\Person;
use AppBundle\Entity\Scout;
use AppBundle\Entity\User;
use Tests\AppBundle\KernelTest;

/**
 * Class ScoutTest.
 *
 * @covers \AppBundle\Entity\Scout
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

        $module = new Module('Module 1');
        $module2 = new Module('Module 2');

        $person = new Person('Doe', 'John', 'Address');
        $person->setPhone('+41 79 123 45 67');
        $person->setMail('john.doe@example.com');
        $birthDate = new \DateTime();
        $person->setBirthDate($birthDate);

        $em->persist($person);
        $em->flush();

        $user = new User('123456789', 'john.doe@example.com', 'abc123cba');
        $tokenExpireDate = (new \DateTime())->add(new \DateInterval('PT3595S'));
        $user->setAccessTokenExpireDate($tokenExpireDate);
        $user->addGroup($group);

        $em->persist($user);
        $em->flush();

        $scout = new Scout($user);
        $scout->addModule($module);
        $scout->addModule($module2);

        $this->assertEquals($user->getEmail(), $scout->getUser()->getEmail());
        $this->assertCount(2, $scout->getModules());

        $em->persist($scout);
        $em->flush();

        $scout->removeModule($module2);

        $this->assertCount(1, $scout->getModules());
    }
}
