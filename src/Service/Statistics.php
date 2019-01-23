<?php

namespace App\Service;


use App\Entity\Equipment;

class Statistics
{

    private $days;
    private $hoursPerDay;
    private $failures;
    private $number;


    public function __construct(int $days, int $hoursPerDay, int $failures, int $number = 1)
    {
        $this->days = $days;
        $this->hoursPerDay = $hoursPerDay;
        $this->failures = $failures;
        $this->number = $number;
    }

    public function getMTBF()
    {
        return $this->days * $this->hoursPerDay * $this->number / $this->failures;

    }


}