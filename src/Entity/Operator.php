<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OperatorRepository")
 * @UniqueEntity(fields={"name"})
 */
class Operator
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Transportation", mappedBy="operators")
     */
    private $transportations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="operator")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Site")
     */
    private $site;



    public function __construct()
    {

        $this->users = new ArrayCollection();
        $this->transportations = new ArrayCollection();
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


    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setOperator($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getOperator() === $this) {
                $user->setOperator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transportation[]
     */
    public function getTransportations(): Collection
    {
        return $this->transportations;
    }

    public function addTransportation(Transportation $transportation): self
    {
        if (!$this->transportations->contains($transportation)) {
            $this->transportations[] = $transportation;
        }

        return $this;
    }

    public function removeTransportation(Transportation $transportation): self
    {
        if ($this->transportations->contains($transportation)) {
            $this->transportations->removeElement($transportation);
        }

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




}
