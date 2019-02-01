<?php
/**
 * Created by PhpStorm.
 * User: Keigo
 * Date: 25/01/2019
 * Time: 16:53
 */

namespace App\Services;


use App\Entity\Statistics;

class MTTRStatistics
{


    /**
     * @var Statistics
     */
    private $statistics;

    public function __construct(Statistics $statistics)
    {

        $this->statistics = $statistics;
    }


    public function getMTTR()
    {
        return $this->statistics->getHoursRepair() / $this->statistics->getFailures();
    }

}