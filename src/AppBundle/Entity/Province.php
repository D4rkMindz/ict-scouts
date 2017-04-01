<?php

namespace AppBundle\Entity;

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
     * Province constructor.
     *
     * @param string $name
     * @param string $nameShort
     */
    public function __construct(string $name, string $nameShort)
    {
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
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }
}
