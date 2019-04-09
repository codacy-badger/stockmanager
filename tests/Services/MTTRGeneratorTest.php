<?php

namespace App\Tests\Services;

use App\Services\MTTRGenerator;
use PHPUnit\Framework\TestCase;

class MTTRGeneratorTest extends TestCase
{

    /**
     *  someRandomMTTR
     */
    public function testMMTRCompute($timeToRepair, $numberOfFailures, $expectedResult)
    {
        $mttrGenerator = new MTTRGenerator();

        $this->assertEquals($expectedResult, $mttrGenerator->generate($timeToRepair, $numberOfFailures));
    }

    public function someRandomMTTR()
    {
        return [
            [2500, 5, 500],
            [2500, 0, null],
        ];
    }
}
