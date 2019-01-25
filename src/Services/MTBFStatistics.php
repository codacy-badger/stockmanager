<?php

namespace App\Services;



class MTBFStatistics
{


    /**
     * Mean Time Before Failure
     * @return float|int
     */
    public function getMTBF(int $days, int $hoursPerDay, int $number, int $failures)
    {
        return $days * $hoursPerDay * $number / $failures;

    }


}