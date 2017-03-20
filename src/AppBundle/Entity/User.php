<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User.
 *
 * @ORM\Table(name="app_user")
 * @ORM\Entity
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="google_id", type="string", unique=true)
     */
    private $googleId;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string")
     */
    private $email;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Group", inversedBy="users", cascade={"all"})
     * @ORM\JoinTable(
     *     name="app_user_has_app_group", joinColumns={
     *          @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     *     }
     * )
     */
    private $groups;

    /**
     * @var string
     *
     * @ORM\Column(name="access_token", type="string", nullable=true)
     */
    private $accessToken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="access_token_expire_date", type="datetime", nullable=true)
     */
    private $accessTokenExpireDate;

    /**
     * @var Person
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Person", inversedBy="user")
     * @ORM\JoinColumn(name="person_id")
     */
    private $person;

    /**
     * User constructor.
     *
     * @param Person $person
     * @param string $googleId
     * @param string $email
     * @param string $accessToken
     */
    public function __construct(Person $person, string $googleId, string $email, string $accessToken = null)
    {
        $this->groups = new ArrayCollection();
        $this->person = $person;
        $this->googleId = $googleId;
        $this->email = $email;
        $this->accessToken = $accessToken;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get person.
     *
     * @return Person
     */
    public function getPerson(): Person
    {
        return $this->person;
    }

    /**
     * Get googleId.
     *
     * @return string
     */
    public function getGoogleId(): string
    {
        return $this->googleId;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return Collection
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    /**
     * @param Group $group
     */
    public function addGroup(Group $group): void
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
        }
    }

    /**
     * @param Group $group
     */
    public function removeGroup(Group $group): void
    {
        $this->groups->removeElement($group);
    }

    /**
     * @param string $accessToken
     */
    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Get accessToken.
     *
     * @return string
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * Set accessTokenExpireDate.
     *
     * @param \DateTime $accessTokenExpireDate
     */
    public function setAccessTokenExpireDate(\DateTime $accessTokenExpireDate): void
    {
        $this->accessTokenExpireDate = $accessTokenExpireDate;
    }

    /**
     * Get accessTokenExpireDate.
     *
     * @return \DateTime
     */
    public function getAccessTokenExpireDate(): ?\DateTime
    {
        return $this->accessTokenExpireDate;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     */
    public function getPassword(): void
    {
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     */
    public function getSalt(): void
    {
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername(): string
    {
        return $this->getEmail();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials(): void
    {
    }

    /**
     * String representation of object.
     *
     * @see \Serializable::serialize()
     */
    public function serialize(): string
    {
        return serialize(
            [
                'id'        => $this->id,
                'person'    => $this->person,
                'googleId'  => $this->googleId,
                'email'     => $this->email,
            ]
        );
    }

    /**
     * Constructs the object.
     *
     * @see \Serializable::unserialize()
     *
     * @param string $serialized
     */
    public function unserialize($serialized): void
    {
        $userArray = unserialize($serialized);
        $this->id = $userArray['id'];
        $this->person = $userArray['person'];
        $this->googleId = $userArray['googleId'];
        $this->email = $userArray['email'];
    }

    /**
     * Get assigned Roles.
     *
     * @return array
     */
    public function getRoles(): array
    {
        $roles = [];
        /** @var Group $group */
        foreach ($this->groups as $group) {
            $roles[] = $group->getRole();
        }

        return array_unique($roles);
    }
}
