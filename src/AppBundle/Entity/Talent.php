<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Talent.
 *
 * @ORM\Table(name="talent")
 * @ORM\Entity
 */
class Talent
{
    public const ACTIVE = 1;
    public const INACTIVE = 2;
    public const FORMER = 3;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Person
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Person", inversedBy="talent", cascade={"all"})
     * @ORM\JoinColumn(name="person_id")
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
     * @var bool
     *
     * @ORM\Column(name="veggie", type="boolean", nullable=true)
     */
    private $veggie;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\TalentStatusHistory", mappedBy="talent")
     */
    private $talentStatusHistory;

    /**
     * Talent constructor.
     *
     * @param Person      $person
     * @param School|null $school
     * @param bool        $veggie
     */
    public function __construct(Person $person, School $school = null, Bool $veggie = false)
    {
        $this->person = $person;
        $this->school = $school;
        $this->veggie = $veggie;
        $this->talentStatusHistory = new ArrayCollection();
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

    /**
     * Add talentStatusHistory.
     *
     * @param TalentStatusHistory $talentStatusHistory
     */
    public function addTalentStatusHistory(TalentStatusHistory $talentStatusHistory): void
    {
        $this->talentStatusHistory->add($talentStatusHistory);
    }

    /**
     * Get talentStatusHistory.
     *
     * @return Collection
     */
    public function getTalentStatusHistory(): ?Collection
    {
        return $this->talentStatusHistory;
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

    /**
     * Set person.
     *
     * @param \AppBundle\Entity\Person $person
     *
     * @return Talent
     */
    public function setPerson(\AppBundle\Entity\Person $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Remove talentStatusHistory.
     *
     * @param \AppBundle\Entity\TalentStatusHistory $talentStatusHistory
     */
    public function removeTalentStatusHistory(\AppBundle\Entity\TalentStatusHistory $talentStatusHistory)
    {
        $this->talentStatusHistory->removeElement($talentStatusHistory);
    }
}
