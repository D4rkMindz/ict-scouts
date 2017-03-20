<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Person;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Tests\AppBundle\KernelTest;

/**
 * Class UserTest.
 *
 * @covers \AppBundle\Entity\User
 */
class UserTest extends KernelTest
{
    public function testGetterAndSetter()
    {
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $group = $entityManager->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_ADMIN']);
        $group1 = $entityManager->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_SCOUT']);

        $person = new Person('Doe', 'John');
        $entityManager->persist($person);

        $user = new User($person, '123456789', 'john.doe@example.com', 'abc123cba');
        $tokenExpireDate = (new \DateTime())->add(new \DateInterval('PT3595S'));
        $user->setAccessTokenExpire($tokenExpireDate);
        $user->addGroup($group);
        $user->addGroup($group1);

        $this->assertNull($user->getId());
        $this->assertEquals(123456789, $user->getGoogleId());
        $this->assertEquals('john.doe@example.com', $user->getEmail());
        $this->assertEquals('abc123cba', $user->getAccessToken());
        $this->assertEquals($tokenExpireDate, $user->getAccessTokenExpire());
        $this->assertInstanceOf(ArrayCollection::class, $user->getGroups());
        $this->assertCount(2, $user->getGroups());

        $entityManager->persist($user);
        $entityManager->flush();

        $this->assertTrue(is_array($user->getRoles()));
        $this->assertCount(2, $user->getRoles());
        $this->assertEquals(1, $user->getId());
        $this->assertEquals(null, $user->getPassword());
        $this->assertEquals(null, $user->getSalt());
        $this->assertEquals('john.doe@example.com', $user->getUsername());
        $this->assertEquals(null, $user->eraseCredentials());
    }

    public function testSerialization()
    {
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $person = new Person('Doe', 'John');
        $entityManager->persist($person);

        $user = new User($person, '123456789', 'john.doe@example.com');
        $user->setAccessToken('abc123cba');
        $tokenExpireDate = (new \DateTime())->add(new \DateInterval('PT3595S'));
        $user->setAccessTokenExpire($tokenExpireDate);

        $serialized = $user->serialize();

        $this->assertTrue(is_string($serialized));

        $person2 = new Person('Doe', 'Jane');
        $entityManager->persist($person);

        $newUser = new User($person2, '', '');
        $newUser->unserialize($serialized);

        $this->assertInstanceOf(User::class, $newUser);
        $this->assertEquals(null, $newUser->getId());
        $this->assertEquals(123456789, $newUser->getGoogleId());
    }
}
