<?php

namespace App\Tests\Services;

use App\Services\DateDiffHour;
use PHPUnit\Framework\TestCase;

class DateDiffHourTest extends TestCase
{

    /**
     * @dataProvider randomDates
     * @param $dateEnd
     * @param $dateStart
     */
    public function testComputeHours($dateEnd, $dateStart, $expectedResult)
    {
        $dateDiff = new DateDiffHour();

        $this->assertEquals($expectedResult, $dateDiff->getDiff($dateEnd, $dateStart));
    }


    public function randomDates()
    {

        $date = new \DateTime('2019-01-05');
        $date->setTime(10,00);

        $date1 = new \DateTime('2019-01-05');
        $date1->setTime(10, 31);

        return[
            [new \DateTime('2019-01-10'), new \DateTime('2019-01-01'), 216 ],
            [$date1, $date, 1 ],

        ];
    }
}
