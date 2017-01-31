<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TalentStatusHistory.
 *
 * @ORM\Table(name="talent_status_history")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TalentStatusHistoryRepository")
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Talent", inversedBy="talentStatusHistories", cascade={"all"})
     * @ORM\JoinColumn(name="talent_id", nullable=true, referencedColumnName="person_id")
     */
    private $talent;

    /**
     * @var TalentStatus
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TalentStatus", inversedBy="talentStatusHistories", cascade={"all"})
     * @ORM\JoinColumn(name="talent_status_id", nullable=true)
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
     * @param TalentStatus $status
     * @param \DateTime    $date   (optional)
     */
    public function __construct(Talent $talent, TalentStatus $status, $date = null)
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get talent.
     *
     * @return string
     */
    public function getTalent()
    {
        return $this->talent;
    }

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get changeDate.
     *
     * @return \DateTime
     */
    public function getChangeDate()
    {
        return $this->changeDate;
    }
}
