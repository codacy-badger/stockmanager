<?php
/**
 * Created by PhpStorm.
 * User: Keigo
 * Date: 25/01/2019
 * Time: 16:55
 */

namespace App\Services;


class RateStatistics
{
    public function getRate(float $mtbf, float $mttr)
    {
        return $mtbf / ($mtbf + $mttr);
    }
}