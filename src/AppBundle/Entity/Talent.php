<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
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

    public function __construct(Person $person, User $user)
    {
        $this->person = $person;
        $this->user = $user;
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
     * @param string $school
     *
     * @return Talent
     */
    public function setSchool($school)
    {
        $this->school = $school;

        return $this;
    }

    /**
     * Get school.
     *
     * @return School
     */
    public function getSchool()
    {
        return $this->school;
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
     * Set veggie.
     *
     * @param bool $veggie
     *
     * @return Talent
     */
    public function setVeggie($veggie)
    {
        $this->veggie = $veggie;

        return $this;
    }

    /**
     * Get veggie.
     *
     * @return bool
     */
    public function getVeggie()
    {
        return $this->veggie;
    }
}
