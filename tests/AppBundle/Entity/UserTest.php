<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\User;
use Tests\AppBundle\KernelTest;

/**
 * Class UserTest.
 */
class UserTest extends KernelTest
{
    public function testGetterAndSetter()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $group = $em->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_ADMIN']);
        $group1 = $em->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_SCOUT']);

        $authorizationChecker = $this->getContainer()->get('security.authorization_checker');

        $user = new User();
        $user->setGoogleId(123456789);
        $user->setGivenName('John');
        $user->setFamilyName('Doe');
        $user->setEmail('john.doe@example.com');
        $user->setAccessToken('abc123cba');
        $tokenExpireDate = (new \DateTime())->add(new \DateInterval('PT3595S'));
        $user->setAccessTokenExpireDate($tokenExpireDate);
        $createdAtDate = new \DateTime();
        $user->setCreatedAt($createdAtDate);
        $updatedAtDate = new \DateTime();
        $user->setUpdatedAt($updatedAtDate);
        $deletedAtDate = new \DateTime();
        $user->setDeletedAt(null);
        $user->setGroups([$group]);
        $user->addGroup($group1);

        $this->assertNull($user->getId());
        $this->assertEquals(123456789, $user->getGoogleId());
        $this->assertEquals('John', $user->getGivenName());
        $this->assertEquals('Doe', $user->getFamilyName());
        $this->assertEquals('john.doe@example.com', $user->getEmail());
        $this->assertEquals('abc123cba', $user->getAccessToken());
        $this->assertEquals($tokenExpireDate, $user->getAccessTokenExpireDate());
        $this->assertEquals($createdAtDate, $user->getCreatedAt());
        $this->assertEquals($updatedAtDate, $user->getUpdatedAt());
        $this->assertNull($user->getDeletedAt());
        $this->assertTrue(is_array($user->getGroups()));
        $this->assertEquals(count($user->getGroups()), 2);

        $em->persist($user);
        $em->flush();

        $this->assertTrue(is_array($user->getRoles()));
        $this->assertEquals(count($user->getRoles()), 3);
        $this->assertEquals(1, $user->getId());
        $this->assertEquals(null, $user->getPassword());
        $this->assertEquals(null, $user->getSalt());
        $this->assertEquals('John Doe', $user->getUsername());
        $this->assertEquals(null, $user->eraseCredentials());
    }

    public function testSerialization()
    {
        $user = new User();
        $user->setGoogleId(123456789);
        $user->setGivenName('John');
        $user->setFamilyName('Doe');
        $user->setEmail('john.doe@example.com');
        $user->setAccessToken('abc123cba');
        $tokenExpireDate = (new \DateTime())->add(new \DateInterval('PT3595S'));
        $user->setAccessTokenExpireDate($tokenExpireDate);
        $createdAtDate = new \DateTime();
        $user->setCreatedAt($createdAtDate);
        $updatedAtDate = new \DateTime();
        $user->setUpdatedAt($updatedAtDate);
        $deletedAtDate = new \DateTime();
        $user->setDeletedAt(null);

        $serialized = $user->serialize();

        $this->assertTrue(is_string($serialized));

        $newUser = new User();
        $newUser->unserialize($serialized);

        $this->assertTrue($newUser instanceof User);
        $this->assertEquals(null, $newUser->getId());
        $this->assertEquals(123456789, $newUser->getGoogleId());
    }
}
