<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Search
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\SearchRepository")
 */
class Search
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Equipment")
     */
    private $equipment;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Site")
     */
    private $site;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Brand")
     */
    private $brand;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Category")
     */
    private $category;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Operator")
     */
    private $operator;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Contract")
     */
    private $contract;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Transportation")
     */
    private $transportation;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getOperator(): ?Operator
    {
        return $this->operator;
    }

    public function setOperator(?Operator $operator): self
    {
        $this->operator = $operator;

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

    public function getTransportation(): ?Transportation
    {
        return $this->transportation;
    }

    public function setTransportation(?Transportation $transportation): self
    {
        $this->transportation = $transportation;

        return $this;
    }


}
