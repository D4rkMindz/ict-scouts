<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Class Group.
 *
 * @ORM\Table(name="app_group")
 * @ORM\Entity
 */
class Group implements RoleInterface, \Serializable
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string", length=50)
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(name="role", type="string", length=50, unique=true)
     *
     * @var string
     */
    protected $role;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="groups", cascade={"all"})
     *
     * @var ArrayCollection
     */
    protected $users;

    public function __construct(string $name, string $role)
    {
        $this->name = $name;
        $this->role = $role;
        $this->users = $this->users = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @return array|ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param array|ArrayCollection $users
     *
     * @return $this
     */
    public function setUsers(array $users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(
            [
                $this->id,
                $this->name,
                $this->role,
                $this->users,
            ]
        );
    }

    /**
     * @see \Serializable::unserialize()
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list($this->id, $this->name, $this->role, $this->users) = unserialize($serialized);
    }
}
