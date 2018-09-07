<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IssueSymptomRepository")
 */
class IssueSymptom
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Issue")
     */
    private $issue;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BreakdownSymptom")
     */
    private $symptom;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getSymptom(): ?BreakdownSymptom
    {
        return $this->symptom;
    }

    public function setSymptom(?BreakdownSymptom $symptom): self
    {
        $this->symptom = $symptom;

        return $this;
    }
}
