<?php

namespace App\Tests\Services;

use App\Services\RateStatistics;
use PHPUnit\Framework\TestCase;

class RateStatisticsTest extends TestCase
{

    /**
     * @dataProvider someRandomValues
     * @param $mtbf
     * @param $mttr
     * @param $expectedResult
     */
    public function testComputeRate($mtbf, $mttr, $expectedResult)
    {
        $rateGenerator = new RateStatistics();

        $this->assertEquals($expectedResult, $rateGenerator->getRate($mtbf, $mttr));
    }

    public function someRandomValues()
    {
        return [
            [500, 500, 0.5],

        ];
    }
}
