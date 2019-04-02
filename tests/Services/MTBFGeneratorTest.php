<?php

namespace App\Tests;

use App\Services\MTBFGenerator;

use PHPUnit\Framework\TestCase;

class MTBFGeneratorTest extends TestCase
{
    public function testMTBFResult()
    {
        $reportGenerator = new MTBFGenerator();


        $result = $reportGenerator->generate(90, 19, 785, 143);

        $this->assertEquals('9387.062937062938', $result);
    }
}
