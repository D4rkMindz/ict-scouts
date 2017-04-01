<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Person.
 *
 * @ORM\Table(name="person")
 * @ORM\Entity
 */
class Person
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
     * @var Scout
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Scout", mappedBy="person")
     */
    private $scout;

    /**
     * @var Talent
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Talent", mappedBy="person")
     */
    private $talent;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", mappedBy="person")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="family_name", type="string")
     */
    private $familyName;

    /**
     * @var string
     *
     * @ORM\Column(name="given_name", type="string")
     */
    private $givenName;

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
     * @var string
     *
     * @ORM\Column(name="phone", type="string", nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", nullable=true)
     */
    private $mail;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth_date", type="date", nullable=true)
     */
    private $birthDate;

    /**
     * Person constructor.
     *
     * @param string         $familyName
     * @param string         $givenName
     * @param Address|null   $address
     * @param string|null    $phone
     * @param string|null    $mail
     * @param \DateTime|null $birthDate
     */
    public function __construct(
        string $familyName,
        string $givenName,
        Address $address = null,
        string $phone = null,
        string $mail = null,
        \DateTime $birthDate = null
    ) {
        $this->familyName = $familyName;
        $this->givenName = $givenName;
        $this->address = $address;
        $this->phone = $phone;
        $this->mail = $mail;
        $this->birthDate = $birthDate;
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
     * Get scout.
     *
     * @return Scout
     */
    public function getScout(): ?Scout
    {
        return $this->scout;
    }

    /**
     * Get talent.
     *
     * @return Talent
     */
    public function getTalent(): ?Talent
    {
        return $this->talent;
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set familyName.
     *
     * @param string $familyName
     */
    public function setFamilyName(string $familyName): void
    {
        $this->familyName = $familyName;
    }

    /**
     * Get familyName.
     *
     * @return string
     */
    public function getFamilyName(): string
    {
        return $this->familyName;
    }

    /**
     * Set givenName.
     *
     * @param string $givenName
     */
    public function setGivenName(string $givenName): void
    {
        $this->givenName = $givenName;
    }

    /**
     * Get givenName.
     *
     * @return string
     */
    public function getGivenName(): string
    {
        return $this->givenName;
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
     * @return string|null
     */
    public function getStreet(): ?string
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
     * @return string|null
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
     * @return Zip|null
     */
    public function getZip(): ?Zip
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
     * @return Province|null
     */
    public function getProvince(): ?Province
    {
        return $this->province;
    }

    /**
     * Set phone.
     *
     * @param string $phone
     *
     * @return Person
     */
    public function setPhone(string $phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone.
     *
     * @return string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * Set mail.
     *
     * @param string $mail
     *
     * @return Person
     */
    public function setMail(string $mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail.
     *
     * @return string
     */
    public function getMail(): ?string
    {
        return $this->mail;
    }

    /**
     * Set birthDate.
     *
     * @param \DateTime|null $birthDate
     *
     * @return Person
     */
    public function setBirthDate(\DateTime $birthDate=null)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate.
     *
     * @return \DateTime
     */
    public function getBirthDate(): ?\DateTime
    {
        return $this->birthDate;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize(): string
    {
        return serialize(
            [
                $this->id,
                $this->familyName,
                $this->givenName,
                $this->address,
                $this->phone,
                $this->mail,
                $this->birthDate,
            ]
        );
    }

    /**
     * @see \Serializable::unserialize()
     *
     * @param string $serialized
     */
    public function unserialize($serialized): void
    {
        list(
            $this->id,
            $this->familyName,
            $this->givenName,
            $this->address,
            $this->phone,
            $this->mail,
            $this->birthDate
        ) = unserialize($serialized);
    }
}
