<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Module. Topic that the talents learn about.
 *
 * @ORM\Table(name="module")
 * @ORM\Entity
 */
class Module
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", unique=true)
     */
    private $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Scout", mappedBy="modules", cascade={"all"})
     */
    private $scouts;

    /**
     * @var Talent
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Talent", mappedBy="modules", cascade={"all"})
     */
    private $talents;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ModulePart", mappedBy="module", cascade={"all"})
     */
    private $moduleParts;

    /**
     * Module constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->scouts = new ArrayCollection();
        $this->talents = new ArrayCollection();
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
     * Set name.
     *
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Collection
     */
    public function getScouts(): Collection
    {
        return $this->scouts;
    }

    /**
     * @param Scout $scout
     *
     * @return Module
     */
    public function addScout(Scout $scout): Module
    {
        if (!$this->scouts->contains($scout)) {
            $this->scouts->add($scout);
        }

        return $this;
    }

    /**
     * @param Scout $scout
     *
     * @return Module
     */
    public function removeScout(Scout $scout): Module
    {
        $this->scouts->removeElement($scout);

        return $this;
    }

    /**
     * @return Talent
     */
    public function getTalents(): Talent
    {
        return $this->talents;
    }

    /**
     * @param Talent $talent
     *
     * @return Module
     */
    public function addTalent(Talent $talent): Module
    {
        if (!$this->talents->contains($talent)) {
            $this->talents->add($talent);
        }

        return $this;
    }

    /**
     * @param Talent $talent
     *
     * @return Module
     */
    public function removeTalent(Talent $talent): Module
    {
        $this->talents->removeElement($talent);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getModuleParts(): Collection
    {
        return $this->moduleParts;
    }

    /**
     * @param ModulePart $modulePart
     *
     * @return Module
     */
    public function addModulePart(ModulePart $modulePart): Module
    {
        if (!$this->talents->contains($modulePart)) {
            $this->talents->add($modulePart);
        }

        return $this;
    }

    /**
     * @param ModulePart $modulePart
     *
     * @return Module
     */
    public function removeModulePart(ModulePart $modulePart): Module
    {
        $this->talents->removeElement($modulePart);

        return $this;
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
            ]
        );
    }

    /**
     * @see \Serializable::unserialize()
     *
     * @param string $serialized
     */
    public function unserialize(string $serialized): void
    {
        list($this->id, $this->name) = unserialize($serialized);
    }
}
