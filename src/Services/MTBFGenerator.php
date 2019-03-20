<?php
/**
 * Created by PhpStorm.
 * User: Keigo
 * Date: 20/03/2019
 * Time: 12:46
 */

namespace App\Services;



class MTBFGenerator
{
    /**
     * Return MTBF
     *
     * @param int $days
     * @param int $hourPerDay
     * @param int $quantity
     * @param int $failures
     * @return float|int
     */
    public function generate(int $days, int $hourPerDay, int $quantity, int $failures = 1)
    {
        if($failures == 0){
            return null;
        }

        return $days * $hourPerDay * $quantity / $failures;
    }
}