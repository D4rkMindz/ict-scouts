<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=50, unique=true)
     */
    protected $role;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="groups", cascade={"all"})
     */
    protected $users;

    /**
     * Group constructor.
     *
     * @param string $name
     * @param string $role
     */
    public function __construct(string $name, string $role)
    {
        $this->name = $name;
        $this->role = $role;
        $this->users = $this->users = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): ?int
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
     * @return Collection
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * @param User $user
     */
    public function addUser(User $user): void
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }
    }

    /**
     * @param User $user
     */
    public function removeUser(User $user): void
    {
        $this->users->removeElement($user);
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize(): string
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
    public function unserialize($serialized): void
    {
        list($this->id, $this->name, $this->role, $this->users) = unserialize($serialized);
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Group
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set role.
     *
     * @param string $role
     *
     * @return Group
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }
}
