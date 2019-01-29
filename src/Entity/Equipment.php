<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EquipmentRepository")
 * @UniqueEntity(fields={"serial"})
 */
class Equipment
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
    private $serial;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Brand")
     */
    private $brand;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contract")
     */
    private $contract;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Issue", mappedBy="equipment" )
     */
    private $issues;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EquipmentStatus", mappedBy="equipment")
     */
    private $status;

    public function __construct()
    {
        $this->issues = new ArrayCollection();
        $this->status = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSerial(): ?string
    {
        return $this->serial;
    }

    public function setSerial(string $serial): self
    {
        $this->serial = $serial;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getContract(): ?Contract
    {
        return $this->contract;
    }

    public function setContract(?Contract $contract): self
    {
        $this->contract = $contract;

        return $this;
    }

    /**
     * @return Collection|Issue[]
     */
    public function getIssues(): Collection
    {
        return $this->issues;
    }

    public function addIssue(Issue $issue): self
    {
        if (!$this->issues->contains($issue)) {
            $this->issues[] = $issue;
            $issue->setEquipment($this);
        }

        return $this;
    }

    public function removeIssue(Issue $issue): self
    {
        if ($this->issues->contains($issue)) {
            $this->issues->removeElement($issue);
            // set the owning side to null (unless already changed)
            if ($issue->getEquipment() === $this) {
                $issue->setEquipment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EquipmentStatus[]
     */
    public function getStatus(): Collection
    {
        return $this->status;
    }

    public function addStatus(EquipmentStatus $status): self
    {
        if (!$this->status->contains($status)) {
            $this->status[] = $status;
            $status->setEquipment($this);
        }

        return $this;
    }

    public function removeStatus(EquipmentStatus $status): self
    {
        if ($this->status->contains($status)) {
            $this->status->removeElement($status);
            // set the owning side to null (unless already changed)
            if ($status->getEquipment() === $this) {
                $status->setEquipment(null);
            }
        }

        return $this;
    }
}
