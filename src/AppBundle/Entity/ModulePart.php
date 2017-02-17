<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModulePart. @TODO: PReimers, please explain this further.
 *
 * @ORM\Table(name="module_part")
 * @ORM\Entity
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
     * @ORM\Column(name="name", type="string")
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

    public function __construct()
    {
        $this->name = '';
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
     * Get module.
     *
     * @return Module
     */
    public function getModule(): ?Module
    {
        return $this->module;
    }

    /**
     * Set module.
     *
     * @param Module $module
     */
    public function setModule(Module $module): void
    {
        $this->module = $module;
    }

    /**
     * Set workshop.
     *
     * @param string $workshop
     */
    public function setWorkshop($workshop): void
    {
        $this->workshop = $workshop;
    }

    /**
     * Get workshop.
     *
     * @return Workshop
     */
    public function getWorkshop(): ?Workshop
    {
        return $this->workshop;
    }

    /**
     * Set camp.
     *
     * @param string $camp
     */
    public function setCamp($camp): void
    {
        $this->camp = $camp;
    }

    /**
     * Get camp.
     *
     * @return Camp
     */
    public function getCamp(): ?Camp
    {
        return $this->camp;
    }

    /**
     * Set cast.
     *
     * @param string $cast
     */
    public function setCast($cast): void
    {
        $this->cast = $cast;
    }

    /**
     * Get cast.
     *
     * @return Cast
     */
    public function getCast(): ?Cast
    {
        return $this->cast;
    }
}
