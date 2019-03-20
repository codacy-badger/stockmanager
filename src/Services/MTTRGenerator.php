<?php
/**
 * Created by PhpStorm.
 * User: Keigo
 * Date: 20/03/2019
 * Time: 12:49
 */

namespace App\Services;


class MTTRGenerator
{
    public function generate(int $timeToRepair, int $numberOfFailures = 1)
    {
        if($numberOfFailures == 0){
            return null;
        }

        return $timeToRepair / $numberOfFailures;
    }
}