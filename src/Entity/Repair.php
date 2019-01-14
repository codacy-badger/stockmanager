<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RepairRepository")
 */
class Repair
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $technician;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Image")
     */
    private $image;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Symptom")
     */
    private $symptoms;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Part")
     */
    private $parts;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $degradation;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $noBreakdown;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $TimeToRepair;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $StatsDownload;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $SoftUpload;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $softVersion;



    public function __construct()
    {
        $this->symptoms = new ArrayCollection();
        $this->parts = new ArrayCollection();

    }

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

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTechnician(): ?User
    {
        return $this->technician;
    }

    public function setTechnician(?User $technician): self
    {
        $this->technician = $technician;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Symptom[]
     */
    public function getSymptoms(): Collection
    {
        return $this->symptoms;
    }

    public function addSymptom(Symptom $symptom): self
    {
        if (!$this->symptoms->contains($symptom)) {
            $this->symptoms[] = $symptom;
        }

        return $this;
    }

    public function removeSymptom(Symptom $symptom): self
    {
        if ($this->symptoms->contains($symptom)) {
            $this->symptoms->removeElement($symptom);
        }

        return $this;
    }

    /**
     * @return Collection|Part[]
     */
    public function getParts(): Collection
    {
        return $this->parts;
    }

    public function addPart(Part $part): self
    {
        if (!$this->parts->contains($part)) {
            $this->parts[] = $part;
        }

        return $this;
    }

    public function removePart(Part $part): self
    {
        if ($this->parts->contains($part)) {
            $this->parts->removeElement($part);
        }

        return $this;
    }

    public function getDegradation(): ?bool
    {
        return $this->degradation;
    }

    public function setDegradation(?bool $degradation): self
    {
        $this->degradation = $degradation;

        return $this;
    }

    public function getNoBreakdown(): ?bool
    {
        return $this->noBreakdown;
    }

    public function setNoBreakdown(?bool $noBreakdown): self
    {
        $this->noBreakdown = $noBreakdown;

        return $this;
    }

    public function getTimeToRepair(): ?int
    {
        return $this->TimeToRepair;
    }

    public function setTimeToRepair(?int $TimeToRepair): self
    {
        $this->TimeToRepair = $TimeToRepair;

        return $this;
    }

    public function getStatsDownload(): ?bool
    {
        return $this->StatsDownload;
    }

    public function setStatsDownload(bool $StatsDownload): self
    {
        $this->StatsDownload = $StatsDownload;

        return $this;
    }

    public function getSoftUpload(): ?bool
    {
        return $this->SoftUpload;
    }

    public function setSoftUpload(?bool $SoftUpload): self
    {
        $this->SoftUpload = $SoftUpload;

        return $this;
    }

    public function getSoftVersion(): ?string
    {
        return $this->softVersion;
    }

    public function setSoftVersion(?string $softVersion): self
    {
        $this->softVersion = $softVersion;

        return $this;
    }
}
