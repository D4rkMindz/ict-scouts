<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\User;
use AppBundle\Role;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testGetterAndSetter()
    {
        $user = new User();
        $user->setGoogleId(123456789);
        $user->setRole(2);
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
        $this->assertEquals(2, $user->getRole());
        $this->assertEquals('John', $user->getGivenName());
        $this->assertEquals('Doe', $user->getFamilyName());
        $this->assertEquals('john.doe@example.com', $user->getEmail());
        $this->assertEquals('abc123cba', $user->getAccessToken());
        $this->assertEquals($tokenExpireDate, $user->getAccessTokenExpireDate());
        $this->assertEquals($createdAtDate, $user->getCreatedAt());
        $this->assertEquals($updatedAtDate, $user->getUpdatedAt());
        $this->assertEquals(null, $user->getDeletedAt());

        $this->assertEquals(['ROLE_USER', 'ROLE_'.strtoupper(Role::ROLE_2)], $user->getRoles());
        $this->assertEquals(null, $user->getPassword());
        $this->assertEquals(null, $user->getSalt());
        $this->assertEquals('John Doe', $user->getUsername());
        $this->assertEquals(null, $user->eraseCredentials());
    }
}
