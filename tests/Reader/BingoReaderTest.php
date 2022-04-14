<?php

use App\Reader\BingoReader;
use PHPUnit\Framework\TestCase;

class BingoReaderTest extends TestCase
{
    public static BingoReader $reader;

    public static function setUpBeforeClass(): void
    {
        self::$reader = new BingoReader(__DIR__ . '..\..\fixtures\day-4-testinput.txt');
    }

    public function testReadReturnsMultidimensionalArray()
    {
        $result = self::$reader->read();
        $this->assertIsArray($result);
        $this->assertIsArray($result[0]);
        $this->assertEquals(
            15,
            count($result)
        );
        $this->assertEquals(
            5,
            count($result[0])
        );
        $this->assertIsInt($result[0][0]);
    }

    public function testTurnLineIntoArrayStringOfNumbersIntoArrayOfIntegers()
    {
        $this->assertEquals(
            [1, 2, 3, 4, 5],
            self::$reader->turnLineIntoArray('1 2 3 4 5')
        );
    }

    public function testTurnLineIntoArrayTrimsSpacesAndLineEndings()
    {
        $this->assertEquals(
            [1, 2, 3, 4, 5],
            self::$reader->turnLineIntoArray('1 2    3 4  5\l\n')
        );
    }

    public function testReadReturnsCorrectlyIndexedSubarrays()
    {
        $result = self::$reader->read();
        $this->assertEquals(
            [22, 13, 17, 11, 0],
            $result[0]
        );
        $this->assertEquals(
            [8, 2, 23, 4, 24],
            $result[1]
        );
    }
}