<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Address.
 *
 * @ORM\Entity
 */
class Address
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
    private $state;

    /**
     * @var Province
     */
    private $province;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $streetNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $addressExtra;

    /**
     * Address constructor.
     *
     * @param Province    $province
     * @param string      $state
     * @param string      $street
     * @param string      $streetNumber
     * @param string|null $addressExtra
     */
    public function __construct(Province $province, string $state, string $street, string $streetNumber, string $addressExtra = null)
    {
        $this->province = $province;
        $this->state = $state;
        $this->street = $street;
        $this->streetNumber = $streetNumber;
        $this->addressExtra = $addressExtra;
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
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * @return Province
     */
    public function getProvince(): Province
    {
        return $this->province;
    }

    /**
     * @param Province $province
     */
    public function setProvince(Province $province): void
    {
        $this->province = $province;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param string $street
     */
    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getStreetNumber(): string
    {
        return $this->streetNumber;
    }

    /**
     * @param string $streetNumber
     */
    public function setStreetNumber(string $streetNumber): void
    {
        $this->streetNumber = $streetNumber;
    }

    /**
     * @return string
     */
    public function getAddressExtra(): string
    {
        return $this->addressExtra;
    }

    /**
     * @param string $addressExtra
     */
    public function setAddressExtra(string $addressExtra): void
    {
        $this->addressExtra = $addressExtra;
    }
}
