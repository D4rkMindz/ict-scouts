<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Scout
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
     * @ORM\Column(name="app_user_id", type="string", length=255)
     */
    private $user;

    public function __construct(Person $person, User $user)
    {
        $this->person = $person;
        $this->user = $user;
    }

    /**
     * Get personId
     *
     * @return Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}

