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
}
