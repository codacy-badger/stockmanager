<?php

namespace App\Tests;

use App\Services\MTBFGenerator;

use PHPUnit\Framework\TestCase;

class MTBFGeneratorTest extends TestCase
{

    /**
     * @dataProvider someRandomMTBF
     */
    public function testMTBFResult($days, $hourPerDay, $quantity, $failures, $expectedResult)
    {
        $reportGenerator = new MTBFGenerator();


        $result = $reportGenerator->generate($days, $hourPerDay, $quantity, $failures);

        $this->assertEquals($expectedResult, $result);
    }

    public function someRandomMTBF()
    {
        return [
            [90, 19, 785, 143, '9387.062937062938'],
            [120, 23, 56, 0, null]
        ];
    }
}
