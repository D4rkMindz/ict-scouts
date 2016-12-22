<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testGetterAndSetter()
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

        $this->assertEquals(null, $user->getId());
        $this->assertEquals(123456789, $user->getGoogleId());
        $this->assertEquals('John', $user->getGivenName());
        $this->assertEquals('Doe', $user->getFamilyName());
        $this->assertEquals('john.doe@example.com', $user->getEmail());
        $this->assertEquals('abc123cba', $user->getAccessToken());
        $this->assertEquals($tokenExpireDate, $user->getAccessTokenExpireDate());
        $this->assertEquals($createdAtDate, $user->getCreatedAt());
        $this->assertEquals($updatedAtDate, $user->getUpdatedAt());
        $this->assertEquals(null, $user->getDeletedAt());

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
