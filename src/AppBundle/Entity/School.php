<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Schools that the scouts visit to gather more talents.
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
 * @var string
 *
 * @ORM\Column(type="string", nullable=true)
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
     * @ORM\JoinColumn(name="zip_id", nullable=true)
     */
    private $zip;

    /**
     * @var Province
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Province", cascade={"persist"})
     * @ORM\JoinColumn(name="province_id", nullable=true)
     */
    private $province;

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
        list($this->id, $this->name) = unserialize($serialized);
    }

    /**
     * Add talent
     *
     * @param \AppBundle\Entity\Talent $talent
     *
     * @return School
     */
    public function addTalent(\AppBundle\Entity\Talent $talent)
    {
        $this->talents[] = $talent;

        return $this;
    }

    /**
     * Remove talent
     *
     * @param \AppBundle\Entity\Talent $talent
     */
    public function removeTalent(\AppBundle\Entity\Talent $talent)
    {
        $this->talents->removeElement($talent);
    }
}
