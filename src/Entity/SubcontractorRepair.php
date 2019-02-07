<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubcontractorRepairRepository")
 */
class SubcontractorRepair
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateEntry;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateExit;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Repair", inversedBy="subcontractorRepair", cascade={"persist", "remove"})
     */
    private $repair;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEntry(): ?\DateTimeInterface
    {
        return $this->dateEntry;
    }

    public function setDateEntry(?\DateTimeInterface $dateEntry): self
    {
        $this->dateEntry = $dateEntry;

        return $this;
    }

    public function getDateExit(): ?\DateTimeInterface
    {
        return $this->dateExit;
    }

    public function setDateExit(?\DateTimeInterface $dateExit): self
    {
        $this->dateExit = $dateExit;

        return $this;
    }

    public function getRepair(): ?Repair
    {
        return $this->repair;
    }

    public function setRepair(?Repair $repair): self
    {
        $this->repair = $repair;

        return $this;
    }
}
