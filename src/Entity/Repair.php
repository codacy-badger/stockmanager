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
    private $dateEnd;

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
    private $timeToRepair;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $statsDownload;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $SoftUpload;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $softVersion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Software")
     */
    private $software;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Issue")
     */
    private $issue;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $unavailability;


    /**
     * @ORM\OneToOne(targetEntity="App\Entity\SubcontractorRepair", mappedBy="repair", cascade={"persist", "remove"})
     *
     */
    private $subcontractorRepair;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isGoingToSubcontractor;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Document", orphanRemoval=true, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="document_file_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $document;




    public function __construct()
    {
        $this->symptoms = new ArrayCollection();
        $this->parts = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

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
        return $this->timeToRepair;
    }

    public function setTimeToRepair(?int $timeToRepair): self
    {
        $this->timeToRepair = $timeToRepair;

        return $this;
    }

    public function getStatsDownload(): ?bool
    {
        return $this->statsDownload;
    }

    public function setStatsDownload(bool $statsDownload): self
    {
        $this->statsDownload = $statsDownload;

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

    public function getIssue(): ?Issue
    {
        return $this->issue;
    }

    public function setIssue(?Issue $issue): self
    {
        $this->issue = $issue;

        return $this;
    }

    public function getUnavailability(): ?int
    {
        return $this->unavailability;
    }

    public function setUnavailability(?int $unavailability): self
    {
        $this->unavailability = $unavailability;

        return $this;
    }

    public function getSubcontractorRepair(): ?SubcontractorRepair
    {
        return $this->subcontractorRepair;
    }

    public function setSubcontractorRepair(?SubcontractorRepair $subcontractorRepair): self
    {
        $this->subcontractorRepair = $subcontractorRepair;

        // set (or unset) the owning side of the relation if necessary
        $newRepair = $subcontractorRepair === null ? null : $this;
        if ($newRepair !== $subcontractorRepair->getRepair()) {
            $subcontractorRepair->setRepair($newRepair);
        }

        return $this;
    }

    public function getIsGoingToSubcontractor(): ?bool
    {
        return $this->isGoingToSubcontractor;
    }

    public function setIsGoingToSubcontractor(?bool $isGoingToSubcontractor): self
    {
        $this->isGoingToSubcontractor = $isGoingToSubcontractor;

        return $this;
    }

    public function getSoftware(): ?Software
    {
        return $this->software;
    }

    public function setSoftware(?Software $software): self
    {
        $this->software = $software;

        return $this;
    }

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): self
    {
        $this->document = $document;

        return $this;
    }


}
