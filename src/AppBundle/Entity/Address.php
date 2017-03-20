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
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $addressExtra;

    /**
     * @var Zip
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Zip", cascade={"persist"})
     * @ORM\JoinColumn(name="zip_id")
     */
    private $zip;

    /**
     * @var Province
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Province", cascade={"persist"})
     * @ORM\JoinColumn(name="province_id")
     */
    private $province;

    /**
     * Address constructor.
     *
     * @param string   $street
     * @param Zip      $zip
     * @param Province $province
     * @@param string|null $addressExtra
     */
    public function __construct(
        string $street,
        Zip $zip,
        Province $province,
        string $addressExtra = null
    ) {
        $this->street = $street;
        $this->zip = $zip;
        $this->province = $province;
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
     * Set street.
     *
     * @param string $street
     */
    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    /**
     * Get street.
     *
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * Set addressExtra.
     *
     * @param string $addressExtra
     */
    public function setAddressExtra(string $addressExtra): void
    {
        $this->addressExtra = $addressExtra;
    }

    /**
     * Get addressExtra.
     *
     * @return string
     */
    public function getAddressExtra(): ?string
    {
        return $this->addressExtra;
    }

    /**
     * Set zip.
     *
     * @param Zip $zip
     */
    public function setZip(Zip $zip): void
    {
        $this->zip = $zip;
    }

    /**
     * Get zip.
     *
     * @return Zip
     */
    public function getZip(): Zip
    {
        return $this->zip;
    }

    /**
     * Set province.
     *
     * @param Province $province
     */
    public function setProvince(Province $province): void
    {
        $this->province = $province;
    }

    /**
     * Get province.
     *
     * @return Province
     */
    public function getProvince(): Province
    {
        return $this->province;
    }
}
