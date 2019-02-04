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

    public function __construct()
    {
        $this->partGroups = new ArrayCollection();
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
}
