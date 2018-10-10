<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IssueRepository")
 */
class Issue
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(
     *     max = 200,
     *     maxMessage="Le texte ne doit pas dépasser {{ limit }} caractères."
     * )
     *
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateRequest;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateChecked;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateReady;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateEnd;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="issues")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $technician;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Equipment", inversedBy="issues")
     */
    private $equipment;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Repair")
     */
    private $repair;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Symptom")
     */
    private $symptoms;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transportation")
     */
    private $transportation;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateMessage;



    public function __construct()
    {
        $this->symptoms = new ArrayCollection();
        $this->dateRequest = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRequest(): ?\DateTimeInterface
    {
        return $this->dateRequest;
    }

    public function setDateRequest(\DateTimeInterface $dateRequest): self
    {
        $this->dateRequest = $dateRequest;

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

    public function getDateChecked(): ?\DateTimeInterface
    {
        return $this->dateChecked;
    }

    public function setDateChecked(?\DateTimeInterface $dateChecked): self
    {
        $this->dateChecked = $dateChecked;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getEquipment(): ?Equipment
    {
        return $this->equipment;
    }

    public function setEquipment(?Equipment $equipment): self
    {
        $this->equipment = $equipment;

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

    public function getDateReady(): ?\DateTimeInterface
    {
        return $this->dateReady;
    }

    public function setDateReady(?\DateTimeInterface $dateReady): self
    {
        $this->dateReady = $dateReady;

        return $this;
    }

    public function getTransportation(): ?Transportation
    {
        return $this->transportation;
    }

    public function setTransportation(?Transportation $transportation): self
    {
        $this->transportation = $transportation;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(?\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }


    public function getDateMessage(): ?\DateTimeInterface
    {
        return $this->dateMessage;
    }

    public function setDateMessage(?\DateTimeInterface $dateMessage): self
    {
        $this->dateMessage = $dateMessage;

        return $this;
    }


}
