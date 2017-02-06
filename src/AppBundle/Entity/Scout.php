<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Scout.
 *
 * @ORM\Table(name="scout")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ScoutRepository")
 */
class Scout
{
    /**
     * @var Person
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Person", inversedBy="scouts", cascade={"all"})
     * @ORM\JoinColumn(name="person_id", unique=true)
     */
    private $person;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", inversedBy="scout", cascade={"all"})
     * @ORM\JoinColumn(name="app_user_id")
     */
    private $user;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Module", inversedBy="scouts", cascade={"all"})
     * @ORM\JoinTable(
     *     name="scout_has_module", joinColumns={
     *          @ORM\JoinColumn(name="scout_id", referencedColumnName="person_id")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="module_id", referencedColumnName="id")
     *     }
     * )
     */
    private $modules;

    public function __construct(Person $person, User $user)
    {
        $this->person = $person;
        $this->user = $user;
    }

    /**
     * Get personId.
     *
     * @return Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return array|ArrayCollection
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * @param array|ArrayCollection $modules
     */
    public function setModules(array $modules)
    {
        $this->modules = $modules;
    }

    /**
     * @param Module $module
     *
     * @return $this
     */
    public function addModule(Module $module)
    {
        $this->modules[] = $module;

        return $this;
    }
}
