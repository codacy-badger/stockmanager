<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PartQuantity", mappedBy="part")
     */
    private $quantities;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $threshold;

    public function __construct()
    {
        $this->quantities = new ArrayCollection();
    }

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

    /**
     * @return Collection|PartQuantity[]
     */
    public function getQuantities(): Collection
    {
        return $this->quantities;
    }

    public function addQuantity(PartQuantity $quantity): self
    {
        if (!$this->quantities->contains($quantity)) {
            $this->quantities[] = $quantity;
            $quantity->setPart($this);
        }

        return $this;
    }

    public function removeQuantity(PartQuantity $quantity): self
    {
        if ($this->quantities->contains($quantity)) {
            $this->quantities->removeElement($quantity);
            // set the owning side to null (unless already changed)
            if ($quantity->getPart() === $this) {
                $quantity->setPart(null);
            }
        }

        return $this;
    }

    public function getThreshold(): ?int
    {
        return $this->threshold;
    }

    public function setThreshold(?int $threshold): self
    {
        $this->threshold = $threshold;

        return $this;
    }
}
