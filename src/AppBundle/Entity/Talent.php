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
     * @var Person
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Person", inversedBy="talents", cascade={"all"})
     * @ORM\JoinColumn(name="person_id", unique=true)
     */
    private $person;

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
     * Get person.
     *
     * @return Person
     */
    public function getPerson()
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
