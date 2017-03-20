<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TalentStatusHistory.
 *
 * @ORM\Table(name="talent_status_history")
 * @ORM\Entity
 */
class TalentStatusHistory
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
     * @var Talent
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Talent", inversedBy="talentStatusHistory", cascade={"all"})
     * @ORM\JoinColumn(name="talent_id", nullable=true, referencedColumnName="id")
     */
    private $talent;

    /**
     * @var int
     *
     * @ORM\Column(name="status", nullable=true)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="changeDate", type="datetime")
     */
    private $changeDate;

    /**
     * TalentStatusHistory constructor.
     *
     * @param Talent       $talent
     * @param int          $status
     * @param \DateTime    $date   (optional)
     */
    public function __construct(Talent $talent, int $status, \DateTime $date = null)
    {
        $this->talent = $talent;
        $this->status = $status;
        $this->changeDate = ($date ?: new \DateTime());
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
     * Get talent.
     *
     * @return Talent
     */
    public function getTalent(): Talent
    {
        return $this->talent;
    }

    /**
     * Get status.
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Get changeDate.
     *
     * @return \DateTime
     */
    public function getChangeDate(): \DateTime
    {
        return $this->changeDate;
    }
}
