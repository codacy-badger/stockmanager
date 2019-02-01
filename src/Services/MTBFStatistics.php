<?php

namespace App\Services;


use App\Entity\Statistics;

class MTBFStatistics
{


    /**
     * @var Statistics
     */
    private $statistics;

    public function __construct(Statistics $statistics)
    {

        $this->statistics = $statistics;
    }


    /**
     * Mean Time Before Failure
     * @return float|int
     */
    public function getMTBF()
    {
        return $this->statistics->getDays() * $this->statistics->getHoursPerDay() * $this->statistics->getNumber() / $this->statistics->getFailures();

    }


}