<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModulePart
 *
 * @ORM\Table(name="module_part")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ModulePartRepository")
 */
class ModulePart
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
     * @ORM\Column(name="name", type="string", length=45)
     */
    private $name;

    /**
     * @var Module
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Module", inversedBy="moduleParts", cascade={"all"})
     * @ORM\JoinColumn(name="module_id", nullable=false)
     */
    private $module;

    /**
     * @var Workshop
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Workshop", inversedBy="moduleParts", cascade={"all"})
     * @ORM\JoinColumn(name="workshop_id", nullable=true)
     */
    private $workshop;

    /**
     * @var Camp
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Camp", inversedBy="moduleParts", cascade={"all"})
     * @ORM\JoinColumn(name="camp_id", nullable=true)
     */
    private $camp;

    /**
     * @var Cast
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Cast", inversedBy="moduleParts", cascade={"all"})
     * @ORM\JoinColumn(name="cast_id", nullable=true)
     */
    private $cast;

    public function __construct($name, Module $module)
    {
        $this->name = $name;
        $this->module = $module;
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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get module
     *
     * @return Module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set workshop
     *
     * @param string $workshop
     *
     * @return ModulePart
     */
    public function setWorkshop($workshop)
    {
        $this->workshop = $workshop;

        return $this;
    }

    /**
     * Get workshop
     *
     * @return Workshop
     */
    public function getWorkshop()
    {
        return $this->workshop;
    }

    /**
     * Set camp
     *
     * @param string $camp
     *
     * @return ModulePart
     */
    public function setCamp($camp)
    {
        $this->camp = $camp;

        return $this;
    }

    /**
     * Get camp
     *
     * @return Camp
     */
    public function getCamp()
    {
        return $this->camp;
    }

    /**
     * Set cast
     *
     * @param string $cast
     *
     * @return ModulePart
     */
    public function setCast($cast)
    {
        $this->cast = $cast;

        return $this;
    }

    /**
     * Get cast
     *
     * @return Cast
     */
    public function getCast()
    {
        return $this->cast;
    }
}

