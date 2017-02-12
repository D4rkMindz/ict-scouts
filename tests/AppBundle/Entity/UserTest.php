<?php

namespace Tests\AppBundle\Entity;

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
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $group = $em->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_ADMIN']);
        $group1 = $em->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_SCOUT']);

        $user = new User('123456789', 'john.doe@example.com', 'abc123cba');
        $tokenExpireDate = (new \DateTime())->add(new \DateInterval('PT3595S'));
        $user->setAccessTokenExpireDate($tokenExpireDate);
        $user->addGroup($group);
        $user->addGroup($group1);

        $this->assertNull($user->getId());
        $this->assertEquals(123456789, $user->getGoogleId());
        $this->assertEquals('john.doe@example.com', $user->getEmail());
        $this->assertEquals('abc123cba', $user->getAccessToken());
        $this->assertEquals($tokenExpireDate, $user->getAccessTokenExpireDate());
        $this->assertInstanceOf(ArrayCollection::class, $user->getGroups());
        $this->assertCount(2, $user->getGroups());

        $em->persist($user);
        $em->flush();

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
        $user = new User('123456789', 'john.doe@example.com');
        $user->setAccessToken('abc123cba');
        $tokenExpireDate = (new \DateTime())->add(new \DateInterval('PT3595S'));
        $user->setAccessTokenExpireDate($tokenExpireDate);

        $serialized = $user->serialize();

        $this->assertTrue(is_string($serialized));

        $newUser = new User('', '');
        $newUser->unserialize($serialized);

        $this->assertInstanceOf(User::class, $newUser);
        $this->assertEquals(null, $newUser->getId());
        $this->assertEquals(123456789, $newUser->getGoogleId());
    }
}
