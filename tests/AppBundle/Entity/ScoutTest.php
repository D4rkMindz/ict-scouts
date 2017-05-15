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
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $group = $entityManager->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_SCOUT']);

        $module = new Module();
        $module->setName('Module 1');
        $module2 = new Module();
        $module2->setName('Module 2');

        $person = new Person('Doe', 'John');
        $person->setPhone('+41 79 123 45 67');
        $person->setMail('john.doe@example.com');
        $birthDate = new \DateTime();
        $person->setBirthDate($birthDate);

        $entityManager->persist($person);
        $entityManager->flush();

        $user = new User($person, '123456789', 'john.doe@example.com', 'abc123cba');
        $tokenExpireDate = (new \DateTime())->add(new \DateInterval('PT3595S'));
        $user->setAccessTokenExpire($tokenExpireDate);
        $user->addGroup($group);

        $entityManager->persist($user);
        $entityManager->flush();

        $scout = new Scout($person);
        $scout->addModule($module);
        $scout->addModule($module2);

        $this->assertNull($scout->getId());
        $this->assertEquals($person, $scout->getPerson());
        $this->assertCount(2, $scout->getModules());

        $entityManager->persist($scout);
        $entityManager->flush();

        $scout->removeModule($module2);

        $this->assertEquals(1, $scout->getId());
        $this->assertCount(1, $scout->getModules());
    }
}
