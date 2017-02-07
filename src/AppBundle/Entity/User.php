<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\Column(name="google_id", type="string", length=255, unique=true)
     *
     * @var string
     */
    private $googleId;

    /**
     * @ORM\Column(name="email", type="string", length=255)
     *
     * @var string
     */
    private $email;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Group", inversedBy="users", cascade={"all"})
     * @ORM\JoinTable(
     *     name="app_user_has_app_group", joinColumns={
     *          @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     *     }
     * )
     *
     * @var ArrayCollection
     */
    private $groups;

    /**
     * @var string
     *
     * @ORM\Column(name="access_token", type="string", length=255, nullable=true)
     */
    private $accessToken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="access_token_expire_date", type="datetime", nullable=true)
     */
    private $accessTokenExpireDate;

    /**
     * @var Scout
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Scout", mappedBy="user", cascade={"all"})
     */
    private $scout;

    /**
     * @var Talent
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Talent", mappedBy="user", cascade={"all"})
     */
    private $talent;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set googleId.
     *
     * @param string $googleId
     *
     * @return User
     */
    public function setGoogleId($googleId)
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * Get googleId.
     *
     * @return string
     */
    public function getGoogleId()
    {
        return $this->googleId;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return array|ArrayCollection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param array|ArrayCollection $groups
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;
    }

    /**
     * @param Group $group
     *
     * @return $this
     */
    public function addGroup(Group $group)
    {
        $this->groups[] = $group;

        return $this;
    }

    /**
     * Set accessToken.
     *
     * @param string $accessToken
     *
     * @return User
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get accessToken.
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set accessTokenExpireDate.
     *
     * @param \DateTime $accessTokenExpireDate
     *
     * @return User
     */
    public function setAccessTokenExpireDate($accessTokenExpireDate)
    {
        $this->accessTokenExpireDate = $accessTokenExpireDate;

        return $this;
    }

    /**
     * Get accessTokenExpireDate.
     *
     * @return \DateTime
     */
    public function getAccessTokenExpireDate()
    {
        return $this->accessTokenExpireDate;
    }

    /**
     * Set scout.
     *
     * @param Scout $scout
     *
     * @return User
     */
    public function setScout(Scout $scout)
    {
        $this->scout = $scout;

        return $this;
    }

    /**
     * Get scout.
     *
     * @return Scout
     */
    public function getScout()
    {
        return $this->scout;
    }

    /**
     * Set talent.
     *
     * @param Talent $talent
     *
     * @return User
     */
    public function setTalent(Talent $talent)
    {
        $this->talent = $talent;

        return $this;
    }

    /**
     * Get talent.
     *
     * @return Talent
     */
    public function getTalent()
    {
        return $this->talent;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     */
    public function getPassword()
    {
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     */
    public function getSalt()
    {
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->getEmail();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }

    /**
     * String representation of object.
     *
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(
            [
                'id'         => $this->id,
                'googleId'   => $this->googleId,
                'email'      => $this->email,
            ]
        );
    }

    /**
     * Constructs the object.
     *
     * @param string $serialized
     *
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        $userArray = unserialize($serialized);
        $this->id = $userArray['id'];
        $this->googleId = $userArray['googleId'];
        $this->email = $userArray['email'];
    }

    /**
     * Get asigned Roles.
     *
     * @return array
     */
    public function getRoles()
    {
        $roles = [];
        /** @var Group $group */
        foreach ($this->groups as $group) {
            $roles[] = $group->getRole();
        }

        return array_unique($roles);
    }
}
