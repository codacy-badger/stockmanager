<?php
/**
 * Created by PhpStorm.
 * User: Keigo
 * Date: 25/01/2019
 * Time: 16:53
 */

namespace App\Services;


class MTTRStatistics
{
    public function getMTBF(int $hoursRepair, int $numberFailure)
    {
        return $hoursRepair / $numberFailure;
    }

}