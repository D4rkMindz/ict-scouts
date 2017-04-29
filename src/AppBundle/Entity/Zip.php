<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zip.
 *
 * @ORM\Table(name="zip")
 * @ORM\Entity
 */
class Zip
{
    /**
     * @var int
     *
     * ONRP from the official swiss-post database.
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="zip", type="string")
     */
    private $zip;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string")
     */
    private $city;

    /**
     * @var Province $province
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Province", inversedBy="zips", cascade={"persist"})
     * @ORM\JoinColumn(name="province_id", referencedColumnName="id")
     */
    private $province;

    /**
     * Zip constructor.
     *
     * @param int    $id
     * @param string $zip
     * @param string $city
     */
    public function __construct(int $id, string $zip, string $city)
    {
        $this->id = $id;
        $this->zip = $zip;
        $this->city = $city;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get zip.
     *
     * @return string
     */
    public function getZip(): string
    {
        return $this->zip;
    }

    /**
     * Get city.
     *
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(
            [
                $this->id,
                $this->zip,
                $this->city,
            ]
        );
    }

    /**
     * @see \Serializable::unserialize()
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list($this->id, $this->zip, $this->city) = unserialize($serialized);
    }

    /**
     * String return value of class.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->zip.' '.$this->city;
    }

    /**
     * Set zip
     *
     * @param string $zip
     *
     * @return Zip
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Zip
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Province
     */
    public function getProvince(): ?Province
    {
        return $this->province;
    }

    /**
     * @param Province $province
     */
    public function setProvince(Province $province)
    {
        $this->province = $province;
    }
}
