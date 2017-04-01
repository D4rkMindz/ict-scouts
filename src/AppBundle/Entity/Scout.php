<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Scout.
 *
 * @ORM\Table(name="scout")
 * @ORM\Entity
 */
class Scout
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
     * @var Person
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Person", inversedBy="scout", cascade={"all"})
     * @ORM\JoinColumn(name="person_id")
     */
    private $person;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Module", inversedBy="scouts", cascade={"all"})
     * @ORM\JoinTable(
     *     name="scout_has_module", joinColumns={
     *          @ORM\JoinColumn(name="scout_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="module_id", referencedColumnName="id")
     *     }
     * )
     */
    private $modules;

    /**
     * Scout constructor.
     *
     * @param Person $person
     */
    public function __construct(Person $person)
    {
        $this->modules = new ArrayCollection();
        $this->person = $person;
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
    public function getPerson(): Person
    {
        return $this->person;
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
    public function addModule(Module $module): void
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
     * Set person
     *
     * @param \AppBundle\Entity\Person $person
     *
     * @return Scout
     */
    public function setPerson(\AppBundle\Entity\Person $person = null)
    {
        $this->person = $person;

        return $this;
    }
}
