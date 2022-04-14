<?php

use App\Reader\BingoNumberReader;
use PHPUnit\Framework\TestCase;

class BingoNumberReaderTest extends TestCase
{
    public static BingoNumberReader $reader;

    public static function setUpBeforeClass(): void
    {
        self::$reader = new BingoNumberReader(__DIR__ . '..\..\fixtures\day-4-testinput.txt');
    }

    public function testReadReturnsArrayOfNumbers()
    {
        $result = self::$reader->read();
        $this->assertIsArray($result);
        $this->assertEquals(
            27,
            count($result)
        );
        $this->assertEquals(
            7,
            $result[0]
        );
    }
}