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
    private $dateReturn;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Repair", inversedBy="subcontractorRepair", cascade={"persist"})
     */
    private $repair;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateDispatch;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rma;


    public function __construct()
    {
        $this->setDateEntry(new \DateTime());
    }

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

    public function getDateReturn(): ?\DateTimeInterface
    {
        return $this->dateReturn;
    }

    public function setDateReturn(?\DateTimeInterface $dateReturn): self
    {
        $this->dateReturn = $dateReturn;

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

    public function getDateDispatch(): ?\DateTimeInterface
    {
        return $this->dateDispatch;
    }

    public function setDateDispatch(?\DateTimeInterface $dateDispatch): self
    {
        $this->dateDispatch = $dateDispatch;

        return $this;
    }

    public function getRma(): ?string
    {
        return $this->rma;
    }

    public function setRma(?string $rma): self
    {
        $this->rma = $rma;

        return $this;
    }
}
