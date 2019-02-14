<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 */
class Location
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
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Equipment")
     */
    private $equipment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Site")
     */
    private $site;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isOk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Part")
     */
    private $part1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Part")
     */
    private $part2;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getIsOk(): ?bool
    {
        return $this->isOk;
    }

    public function setIsOk(?bool $isOk): self
    {
        $this->isOk = $isOk;

        return $this;
    }

    public function getPart1(): ?Part
    {
        return $this->part1;
    }

    public function setPart1(?Part $part1): self
    {
        $this->part1 = $part1;

        return $this;
    }

    public function getPart2(): ?Part
    {
        return $this->part2;
    }

    public function setPart2(?Part $part2): self
    {
        $this->part2 = $part2;

        return $this;
    }
}
