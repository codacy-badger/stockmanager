<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StatisticsRepository")
 */
class Statistics
{


    const DELFAULT_HOURSPERDAY = 8;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $days;

    /**
     * @ORM\Column(type="integer")
     */
    private $hoursPerDay;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\Column(type="integer")
     */
    private $failures;

    /**
     * @ORM\Column(type="integer")
     */
    private $hoursRepair;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDays(): ?int
    {
        return $this->days;
    }

    public function setDays(int $days): self
    {
        $this->days = $days;

        return $this;
    }

    public function getHoursPerDay(): ?int
    {
        return $this->hoursPerDay;
    }

    public function setHoursPerDay(int $hoursPerDay = self::DELFAULT_HOURSPERDAY): self
    {
        $this->hoursPerDay = $hoursPerDay;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getFailures(): ?int
    {
        return $this->failures;
    }

    public function setFailures(int $failures): self
    {
        $this->failures = $failures;

        return $this;
    }

    public function getHoursRepair(): ?int
    {
        return $this->hoursRepair;
    }

    public function setHoursRepair(int $hoursRepair): self
    {
        $this->hoursRepair = $hoursRepair;

        return $this;
    }
}
