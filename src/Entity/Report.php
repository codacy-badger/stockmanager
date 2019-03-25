<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReportRepository")
 */
class Report
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="report", cascade={"persist", "remove"})
     */
    private $category;

    /**
     * @ORM\Column(type="date")
     */
    private $startDate;

    /**
     * @ORM\Column(type="date")
     */
    private $endDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $issueQuantity;

    /**
     * @ORM\Column(type="integer")
     */
    private $fakeIssueQuantity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $repairTime;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $mtbf;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $mttr;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     */
    private $rate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $currentIssueQuantity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $subcontractorIssueQuantity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $degradationQuantity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $newIssueQuantity;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $contractualQuantity;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getIssueQuantity(): ?int
    {
        return $this->issueQuantity;
    }

    public function setIssueQuantity(int $issueQuantity): self
    {
        $this->issueQuantity = $issueQuantity;

        return $this;
    }

    public function getFakeIssueQuantity(): ?int
    {
        return $this->fakeIssueQuantity;
    }

    public function setFakeIssueQuantity(int $fakeIssueQuantity): self
    {
        $this->fakeIssueQuantity = $fakeIssueQuantity;

        return $this;
    }

    public function getRepairTime(): ?int
    {
        return $this->repairTime;
    }

    public function setRepairTime(?int $repairTime): self
    {
        $this->repairTime = $repairTime;

        return $this;
    }

    public function getMtbf()
    {
        return $this->mtbf;
    }

    public function setMtbf($mtbf): self
    {
        $this->mtbf = $mtbf;

        return $this;
    }

    public function getMttr()
    {
        return $this->mttr;
    }

    public function setMttr($mttr): self
    {
        $this->mttr = $mttr;

        return $this;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function setRate($rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCurrentIssueQuantity(): ?int
    {
        return $this->currentIssueQuantity;
    }

    public function setCurrentIssueQuantity(?int $currentIssueQuantity): self
    {
        $this->currentIssueQuantity = $currentIssueQuantity;

        return $this;
    }

    public function getSubcontractorIssueQuantity(): ?int
    {
        return $this->subcontractorIssueQuantity;
    }

    public function setSubcontractorIssueQuantity(?int $subcontractorIssueQuantity): self
    {
        $this->subcontractorIssueQuantity = $subcontractorIssueQuantity;

        return $this;
    }

    public function getDegradationQuantity(): ?int
    {
        return $this->degradationQuantity;
    }

    public function setDegradationQuantity(?int $degradationQuantity): self
    {
        $this->degradationQuantity = $degradationQuantity;

        return $this;
    }

    public function getNewIssueQuantity(): ?int
    {
        return $this->newIssueQuantity;
    }

    public function setNewIssueQuantity(?int $newIssueQuantity): self
    {
        $this->newIssueQuantity = $newIssueQuantity;

        return $this;
    }


    public function getContractualQuantity(): ?int
    {
        return $this->contractualQuantity;
    }

    public function setContractualQuantity(?int $contractualQuantity): self
    {
        $this->contractualQuantity = $contractualQuantity;

        return $this;
    }
}
