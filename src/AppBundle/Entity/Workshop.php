<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Workshop.
 *
 * @ORM\Table(name="workshop")
 * @ORM\Entity
 */
class Workshop
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
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var Address
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Address")
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     */
    private $address;

    /**
     * Workshop constructor.
     *
     * @param string         $name
     * @param Address|string $address
     */
    public function __construct(string $name, Address $address)
    {
        $this->name = $name;
        $this->address = $address;
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
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get address.
     *
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(
            [
                $this->id,
                $this->name,
                $this->address,
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
        list($this->id, $this->name, $this->address) = unserialize($serialized);
    }
}
