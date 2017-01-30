<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zip
 *
 * @ORM\Table(name="zip")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ZipRepository")
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
     * @ORM\Column(name="zip", type="string", length=10)
     */
    private $zip;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=100)
     */
    private $city;

	public function __construct(string $zip, string $city)
	{
		$this->zip = $zip;
		$this->city = $city;
	}

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get zip
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
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
}

