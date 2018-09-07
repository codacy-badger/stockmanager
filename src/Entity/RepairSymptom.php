<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RepairSymptomRepository")
 */
class RepairSymptom
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Repair")
     */
    private $repair;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BreakdownSymptom")
     */
    private $breakdownSymptom;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBreakdownSymptom(): ?BreakdownSymptom
    {
        return $this->breakdownSymptom;
    }

    public function setBreakdownSymptom(?BreakdownSymptom $breakdownSymptom): self
    {
        $this->breakdownSymptom = $breakdownSymptom;

        return $this;
    }
}
