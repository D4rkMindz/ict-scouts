<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Schools that the scouts visit to gather more talents.
 *
 * @TODO: Maybe get dataset from examina.
 *
 * @ORM\Table(name="school")
 * @ORM\Entity
 */
class School
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
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Talent", mappedBy="school")
     */
    private $talents;

    /**
     * School constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
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
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    /**
     * @return Collection
     */
    public function getTalents(): Collection
    {
        return $this->talents;
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
