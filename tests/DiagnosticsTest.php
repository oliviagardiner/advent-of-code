<?php

use App\Reader\DiagnosticsReader;
use App\Diagnostics;
use App\Exceptions\NotFileException;
use App\Exceptions\IncorrectExtensionException;
use PHPUnit\Framework\TestCase;

class DiagnosticsTest extends TestCase
{
    public static Diagnostics $diagnostics;

    /**
     * @throws NotFileException
     * @throws IncorrectExtensionException
     */
    public static function getNewDiagnosticsWithTestInput(string $path): Diagnostics
    {
        $testreader = new DiagnosticsReader(__DIR__ . $path);
        return new Diagnostics($testreader);
    }

    public function testProcessLineConvertsStringToCorrectArray()
    {
        self::$diagnostics = self::getNewDiagnosticsWithTestInput('\fixtures\day-3-testinput.txt');
        $this->assertEquals(
            [1, 0, 1, 1, 0, 1, 1],
            self::$diagnostics->processLine('1011011\n')
        );
    }

    public function testGetMostCommonBitReturnsCorrectValue()
    {
        $this->assertEquals(
            1,
            self::$diagnostics::getMostCommonBit([1, 0, 1, 1, 0, 1, 1])
        );
    }

    public function testGetLeastCommonBitReturnsCorrectValue()
    {
        $this->assertEquals(
            0,
            self::$diagnostics::getLeastCommonBit([1, 0, 1, 1, 0, 1, 1])
        );
    }

    public function testGetLeastCommonBitReturnsCorrectValue2()
    {
        $this->assertEquals(
            0,
            self::$diagnostics::getLeastCommonBit([0, 1, 1, 1, 1, 0, 0, 1, 1, 1, 0])
        );
    }

    public function testMostCommonBinaryReturnsCorrectBinary()
    {
        $diagnostics = self::getNewDiagnosticsWithTestInput('\fixtures\day-3-testinput.txt');
        $this->assertEquals(
            '10110',
            $diagnostics->getMostCommonBinaryFromGroups()
        );
    }

    public function testComputeGammaRateReturnsCorrectDecimal()
    {
        $diagnostics = self::getNewDiagnosticsWithTestInput('\fixtures\day-3-testinput.txt');
        $this->assertEquals(
            22,
            $diagnostics->computeGammaRate()
        );
    }

    public function testLeastCommonBinaryReturnsCorrectBinary()
    {
        $diagnostics = self::getNewDiagnosticsWithTestInput('\fixtures\day-3-testinput.txt');
        $this->assertEquals(
            '01001',
            $diagnostics->getLeastCommonBinaryFromGroups()
        );
    }

    public function testComputeEpsilonRateReturnsCorrectDecimal()
    {
        $diagnostics = self::getNewDiagnosticsWithTestInput('\fixtures\day-3-testinput.txt');
        $this->assertEquals(
            9,
            $diagnostics->computeEpsilonRate()
        );
    }

    public function testCalculatesCorrectPowerConsumption()
    {
        $diagnostics = self::getNewDiagnosticsWithTestInput('\fixtures\day-3-testinput.txt');
        $this->assertEquals(
            198,
            $diagnostics->getPowerConsumption()
        );
    }

    public function testLineMatchesCriteriaTrue()
    {
        $this->assertEquals(
            true,
            self::$diagnostics->lineMatchesCriteria('01001', 1, 1)
        );
    }

    public function testLineMatchesCriteriaFalse()
    {
        $this->assertEquals(
            false,
            self::$diagnostics->lineMatchesCriteria('01001', 0, 1)
        );
    }

    public function testFilterReportByCriteria()
    {
        $this->assertEquals(
            ['11110', '10110', '10111', '10101', '11100', '10000', '11001'],
            self::$diagnostics->filterReportByCriteria(0, 1)
        );
    }
    
    public function testComputeOxygenGenRatingBinaryReturnsCorrectBinary()
    {
        $diagnostics = self::getNewDiagnosticsWithTestInput('\fixtures\day-3-testinput.txt');
        $this->assertEquals(
            '10111',
            $diagnostics->computeOxygenGenRatingBinary()
        );
    }

    public function testComputeOxygenGenRatingBinaryReturnsCorrectDecimal()
    {
        $diagnostics = self::getNewDiagnosticsWithTestInput('\fixtures\day-3-testinput.txt');
        $this->assertEquals(
            23,
            $diagnostics->computeOxygenGenRating()
        );
    }

    public function testComputeCo2RatingBinaryReturnsCorrectBinary()
    {
        $diagnostics = self::getNewDiagnosticsWithTestInput('\fixtures\day-3-testinput.txt');
        $this->assertEquals(
            '01010',
            $diagnostics->computeCo2RatingBinary()
        );
    }

    public function testComputeCo2GenRatingBinaryReturnsCorrectDecimal()
    {
        $diagnostics = self::getNewDiagnosticsWithTestInput('\fixtures\day-3-testinput.txt');
        $this->assertEquals(
            10,
            $diagnostics->computeCo2Rating()
        );
    }

    public function testCalculatesCorrectLifeSupportRating()
    {
        $diagnostics = self::getNewDiagnosticsWithTestInput('\fixtures\day-3-testinput.txt');
        $this->assertEquals(
            230,
            $diagnostics->getLifeSupportRating()
        );
    }
}