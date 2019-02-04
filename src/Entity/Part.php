<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PartRepository")
 */
class Part
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reference;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $repairTime;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PartGroup", inversedBy="parts")
     */
    private $partGroup;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

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

    public function getPartGroup(): ?PartGroup
    {
        return $this->partGroup;
    }

    public function setPartGroup(?PartGroup $partGroup): self
    {
        $this->partGroup = $partGroup;

        return $this;
    }
}
