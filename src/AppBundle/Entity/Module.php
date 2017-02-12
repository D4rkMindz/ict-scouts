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
     * Module constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name  = $name;
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
     *
     * @return Module
     */
    public function setName(string $name): Module
    {
        $this->name = $name;

        return $this;
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
     * @return Talent
     */
    public function getTalents(): Talent
    {
        return $this->talents;
    }

    /**
     * @param Talent $talent
     */
    public function addTalent(Talent $talent): void
    {
        if (!$this->talents->contains($talent)) {
            $this->talents->add($talent);
        }
    }

    /**
     * @param Talent $talent
     */
    public function removeTalent(Talent $talent): void
    {
        $this->talents->removeElement($talent);
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

    /**
     * Create Object from Array.
     *
     * @param array $array
     *
     * @return Module
     */
    public static function fromArray(array $array): Module
    {
        return new self($array['name']);
    }
}
