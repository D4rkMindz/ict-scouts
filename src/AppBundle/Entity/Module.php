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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ModulePart", mappedBy="module", cascade={"all"})
     */
    private $moduleParts;

    /**
     * Module constructor.
     */
    public function __construct()
    {
        $this->name = '';
        $this->scouts = new ArrayCollection();
        $this->talents = new ArrayCollection();
        $this->moduleParts = new ArrayCollection();
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
     */
    public function addScout(Scout $scout): void
    {
        if (!$this->scouts->contains($scout)) {
            $this->scouts->add($scout);
        }
    }

    /**
     * @param Scout $scout
     */
    public function removeScout(Scout $scout): void
    {
        $this->scouts->removeElement($scout);
    }

    /**
     * @return Collection|null
     */
    public function getModuleParts(): Collection
    {
        return $this->moduleParts;
    }

    /**
     * @param modulePart $modulePart
     */
    public function addModulePart(ModulePart $modulePart): void
    {
        if (!$this->moduleParts->contains($modulePart)) {
            $this->moduleParts->add($modulePart);
        }
    }

    /**
     * @param ModulePart $modulePart
     */
    public function removeModulePart(ModulePart $modulePart): void
    {
        $this->moduleParts->removeElement($modulePart);
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
