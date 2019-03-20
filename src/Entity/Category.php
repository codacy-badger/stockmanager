<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @UniqueEntity(fields={"name"}, message="La catégorie existe déjà.")
 */
class Category
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Image")
     */
    private $image;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $hoursPerDay;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mtbf;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PartGroup", mappedBy="category")
     */
    private $partGroups;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $contractualQuantity;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isContractual;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Brand", mappedBy="category")
     */
    private $brands;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Report", mappedBy="category", cascade={"persist", "remove"})
     */
    private $report;

    public function __construct()
    {
        $this->partGroups = new ArrayCollection();
        $this->brands = new ArrayCollection();
        $this->report = new ArrayCollection();
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

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getHoursPerDay(): ?int
    {
        return $this->hoursPerDay;
    }

    public function setHoursPerDay(?int $hoursPerDay): self
    {
        $this->hoursPerDay = $hoursPerDay;

        return $this;
    }

    public function getMtbf(): ?int
    {
        return $this->mtbf;
    }

    public function setMtbf(?int $mtbf): self
    {
        $this->mtbf = $mtbf;

        return $this;
    }

    /**
     * @return Collection|PartGroup[]
     */
    public function getPartGroups(): Collection
    {
        return $this->partGroups;
    }

    public function addPartGroup(PartGroup $partGroup): self
    {
        if (!$this->partGroups->contains($partGroup)) {
            $this->partGroups[] = $partGroup;
            $partGroup->setCategory($this);
        }

        return $this;
    }

    public function removePartGroup(PartGroup $partGroup): self
    {
        if ($this->partGroups->contains($partGroup)) {
            $this->partGroups->removeElement($partGroup);
            // set the owning side to null (unless already changed)
            if ($partGroup->getCategory() === $this) {
                $partGroup->setCategory(null);
            }
        }

        return $this;
    }

    public function getContractualQuantity(): ?int
    {
        return $this->contractualQuantity;
    }

    public function setContractualQuantity(?int $contractualQuantity): self
    {
        $this->contractualQuantity = $contractualQuantity;

        return $this;
    }

    public function getIsContractual(): ?bool
    {
        return $this->isContractual;
    }

    public function setIsContractual(bool $isContractual): self
    {
        $this->isContractual = $isContractual;

        return $this;
    }

    /**
     * @return Collection|Brand[]
     */
    public function getBrands(): Collection
    {
        return $this->brands;
    }

    public function addBrand(Brand $brand): self
    {
        if (!$this->brands->contains($brand)) {
            $this->brands[] = $brand;
            $brand->setCategory($this);
        }

        return $this;
    }

    public function removeBrand(Brand $brand): self
    {
        if ($this->brands->contains($brand)) {
            $this->brands->removeElement($brand);
            // set the owning side to null (unless already changed)
            if ($brand->getCategory() === $this) {
                $brand->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Report[]
     */
    public function getReport(): Collection
    {
        return $this->report;
    }

    public function addReport(Report $report): self
    {
        if (!$this->report->contains($report)) {
            $this->report[] = $report;
            $report->setCategory($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->report->contains($report)) {
            $this->report->removeElement($report);
            // set the owning side to null (unless already changed)
            if ($report->getCategory() === $this) {
                $report->setCategory(null);
            }
        }

        return $this;
    }


}
