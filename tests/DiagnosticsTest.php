<?php

use App\Input\TxtInput;
use App\InputReader\TxtReader;
use App\Diagnostics;
use App\Exceptions\NotFileException;
use App\Exceptions\IncorrectExtensionException;
use App\Exceptions\InvalidCommandException;
use App\Exceptions\InvalidNavigationValueException;
use PHPUnit\Framework\TestCase;

class DiagnosticsTest extends TestCase
{
    public static Diagnostics $diagnostics;
    public static ReflectionClass $reflectionClass;

    /**
     * @throws NotFileException
     * @throws IncorrectExtensionException
     */
    public static function getNewDiagnosticsWithTestInput(string $path): Diagnostics
    {
        $testreport = new TxtInput(__DIR__ . $path, 'txt');
        $testreader = new TxtReader();
        $testreader->setInput($testreport);
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
}