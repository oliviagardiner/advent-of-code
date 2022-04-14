<?php

use App\Reader\HydrothermalVentReader;
use PHPUnit\Framework\TestCase;

class HydrothermalVentReaderTest extends TestCase
{
    public static HydrothermalVentReader $reader;

    public static function setUpBeforeClass(): void
    {
        self::$reader = new HydrothermalVentReader(__DIR__ . '..\..\fixtures\day-5-testinput.txt');
    }

    public function testReadReturnsArrayOfNumbers()
    {
        $result = self::$reader->read();
        $this->assertIsArray($result);
        $this->assertEquals(
            10,
            count($result)
        );
    }

    public function testReadReturnsPairsInArray()
    {
        $result = self::$reader->read();
        $pair = $result[0];
        $this->assertIsArray($pair);
        $this->assertEquals(
            2,
            count($pair)
        );
        $this->assertEquals(
            [[0, 9], [5, 9]],
            $pair
        );
    }

    public function testReadReturnsCoordinateIntegersInPairs()
    {
        $result = self::$reader->read();
        $coord = $result[0][0];
        $this->assertIsArray($coord);
        $this->assertEquals(
            2,
            count($coord)
        );
        $this->assertEquals(
            [0, 9],
            $coord
        );
        $this->assertIsInt($coord[0]);
        $this->assertIsInt($coord[1]);
    }
}

