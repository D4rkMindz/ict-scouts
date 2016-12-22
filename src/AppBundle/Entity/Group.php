<?php
/**
 * Created by PhpStorm.
 * User: ashura
 * Date: 22.12.16
 * Time: 14:04
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Class Group
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
     * @var int $id
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string", length=50)
     *
     * @var string $name
     */
    protected $name;

    /**
     * @ORM\Column(name="role", type="string", length=50, unique=true)
     *
     * @var string $roles
     */
    protected $role;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="groups", cascade={"all"})
     *
     * @var ArrayCollection $users
     */
    protected $users;

    public function __construct(string $name, string $role)
    {
        $this->name = $name;
        $this->role = $role;
        $this->users = $this->users = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @return ArrayCollection
     */
    public function getUsers(): ArrayCollection
    {
        return $this->users;
    }

    /**
     * @param ArrayCollection $users
     *
     * @return $this
     */
    public function setUsers(ArrayCollection $users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(
            array(
                $this->id,
                $this->name,
                $this->role,
                $this->users,
            )
        );
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list($this->id, $this->name, $this->role, $this->users) = unserialize($serialized);
    }
}