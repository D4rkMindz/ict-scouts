<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @TODO: extend from person.
 *
 * Scout.
 *
 * @ORM\Table(name="scout")
 * @ORM\Entity
 */
class Scout
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
     * @var User
     *
     * @TODO: Maybe extend from this? Or create a Trait?
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", inversedBy="scout", cascade={"all"})
     * @ORM\JoinColumn(name="app_user_id")
     */
    private $user;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Module", inversedBy="scouts", cascade={"all"})
     * @ORM\JoinTable(
     *     name="scout_has_module", joinColumns={
     *          @ORM\JoinColumn(name="scout_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="module_id", referencedColumnName="id")
     *     }
     * )
     */
    private $modules;

    /**
     * Scout constructor.
     *
     * @param User   $user
     */
    public function __construct(User $user)
    {
        $this->modules = new ArrayCollection();
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Collection
     */
    public function getModules(): Collection
    {
        return $this->modules;
    }

    /**
     * @param Module $module
     */
    public function addModule(Module $module): void
    {
        if (!$this->modules->contains($module)) {
            $this->modules->add($module);
        }
    }

    /**
     * @param Module $module
     */
    public function removeModule(Module $module): void
    {
        $this->modules->removeElement($module);
    }
}
