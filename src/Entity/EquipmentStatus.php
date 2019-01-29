<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EquipmentStatusRepository")
 */
class EquipmentStatus
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startFailure;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endFailure;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Equipment", inversedBy="status")
     */
    private $equipment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartFailure(): ?\DateTimeInterface
    {
        return $this->startFailure;
    }

    public function setStartFailure(?\DateTimeInterface $startFailure): self
    {
        $this->startFailure = $startFailure;

        return $this;
    }

    public function getEndFailure(): ?\DateTimeInterface
    {
        return $this->endFailure;
    }

    public function setEndFailure(?\DateTimeInterface $endFailure): self
    {
        $this->endFailure = $endFailure;

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
}
