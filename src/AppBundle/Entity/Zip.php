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
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
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
     * Zip constructor.
     *
     * @param string $zip
     * @param string $city
     */
    public function __construct(string $zip, string $city)
    {
        $this->zip = $zip;
        $this->city = $city;
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
}
