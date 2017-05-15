<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Province.
 *
 * @ORM\Entity
 */
class Province
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
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=2)
     */
    private $nameShort;

    /**
     * @var Zip|Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Zip", mappedBy="province", cascade={"persist"})
     */
    private $zips;

    /**
     * Province constructor.
     *
     * @param string $name
     * @param string $nameShort
     */
    public function __construct(string $name, string $nameShort)
    {
        $this->zips = new ArrayCollection();
        $this->name = $name;
        $this->nameShort = $nameShort;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getNameShort(): string
    {
        return $this->nameShort;
    }

    /**
     * @param string $nameShort
     */
    public function setNameShort(string $nameShort): void
    {
        $this->nameShort = $nameShort;
    }

    /**
     * @return Zip
     */
    public function getZips()
    {
        return $this->zips;
    }

    /**
     * @param Zip $zip
     */
    public function addZip(Zip $zip)
    {
        if (!$this->zips->contains($zip)) {
            $this->zips->add($zip);
            $zip->setProvince($this);
        }
    }

    /**
     * @param Zip $zip
     */
    public function removeZip(Zip $zip)
    {
        $this->zips->removeElement($zip);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }
}
