<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @TODO: Extends Person.
 *
 * Talent.
 *
 * @ORM\Table(name="talent")
 * @ORM\Entity
 */
class Talent
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var School
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\School", inversedBy="talents", cascade={"all"})
     * @ORM\JoinColumn(name="school_id", nullable=true)
     */
    private $school;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", inversedBy="talent", cascade={"all"})
     * @ORM\JoinColumn(name="app_user_id", nullable=false)
     */
    private $user;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Module", inversedBy="talents", cascade={"all"})
     * @ORM\JoinTable(
     *     name="talent_has_module", joinColumns={
     *          @ORM\JoinColumn(name="talent_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="module_id", referencedColumnName="id")
     *     }
     * )
     */
    private $modules;

    /**
     * @var bool
     *
     * @ORM\Column(name="veggie", type="boolean", nullable=true)
     */
    private $veggie;

    /**
     * Talent constructor.
     *
     * @param Person $person
     * @param User   $user
     * @param bool   $veggie
     */
    public function __construct(Person $person, User $user, Bool $veggie = false)
    {
        $this->person = $person;
        $this->user = $user;
        $this->veggie = $veggie;
    }

    /**
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
     * Set school.
     *
     * @param School $school
     */
    public function setSchool(School $school): void
    {
        $this->school = $school;
    }

    /**
     * Get school.
     *
     * @return School
     */
    public function getSchool(): ?School
    {
        return $this->school;
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Collection
     */
    public function getModules(): Collection
    {
        return $this->modules;
    }

    /**
     * @param Module $module
     */
    public function addModules(Module $module): void
    {
        if (!$this->modules->contains($module)) {
            $this->modules->add($module);
        }
    }

    /**
     * @param Module $module
     */
    public function removeModule(Module $module): void
    {
        $this->modules->removeElement($module);
    }

    /**
     * Set veggie.
     *
     * @param bool $veggie
     */
    public function setVeggie(bool $veggie): void
    {
        $this->veggie = $veggie;
    }

    /**
     * Is veggie.
     *
     * @return bool
     */
    public function isVeggie(): bool
    {
        return $this->veggie;
    }
}
