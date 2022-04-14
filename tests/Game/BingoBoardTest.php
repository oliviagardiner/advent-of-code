<?php

use App\Exceptions\InvalidMarkException;
use App\Game\Bingo\BingoBoard;
use App\Reader\BingoReader;
use PHPUnit\Framework\TestCase;

class BingoBoardTest extends TestCase
{
    public static BingoBoard $board;

    public static function setUpBeforeClass(): void
    {
        $reader = new BingoReader(__DIR__ . '..\..\fixtures\day-4-testinput.txt');
        $result = $reader->read();
        $boarddata = array_slice($result, 0, 5);
        self::$board = new BingoBoard($boarddata);
    }

    public function testGetRowByNumberReturnsCorrectRow()
    {
        $row = self::$board->getRowByNumber(23);
        $this->assertEquals(
            [8, 2, 23, 4, 24],
            $row
        );
    }

    public function testColumnRowByNumberReturnsCorrectColumn()
    {
        $col = self::$board->getColumnByNumber(23);
        $this->assertEquals(
            [17, 23, 14, 3, 20],
            $col
        );
    }

    public function testGetArrayValueReturnsZeroForFullyUnmarked()
    {
        $this->assertEquals(
            0,
            self::$board->getArrayValue([17, 23, 14, 3, 20])
        );
    }

    public function testGetArrayValueReturnsCorrectValueForPartiallyMarked()
    {
        $testrow = [17, 23, 14, 3, 20];
        self::$board->markNumber($testrow[0]);
        self::$board->markNumber($testrow[2]);
        $this->assertEquals(
            2,
            self::$board->getArrayValue($testrow)
        );
    }

    public function testCheckWinConditionReturnsFalseForPartiallyMarked()
    {
        $testrow = [17, 23, 14, 3, 20];
        $this->assertFalse(self::$board->checkWinCondition($testrow));
        self::$board->markNumber($testrow[0]);
        self::$board->markNumber($testrow[2]);
        $this->assertFalse(self::$board->checkWinCondition($testrow));
    }

    public function testCheckArraysForWinnerReturnsFalseForPartiallyMarked()
    {
        $this->assertFalse(self::$board->checkArraysForWinner(23));
    }

    public function testCheckWinConditionReturnsTrueForFullyMarked()
    {
        $testrow = [17, 23, 14, 3, 20];
        self::$board->markNumber($testrow[0]);
        self::$board->markNumber($testrow[1]);
        self::$board->markNumber($testrow[2]);
        self::$board->markNumber($testrow[3]);
        self::$board->markNumber($testrow[4]);
        $this->assertTrue(self::$board->checkWinCondition($testrow));
    }

    public function testCheckArraysForWinnerReturnsTrueForFullyMarked()
    {
        $this->assertTrue(self::$board->checkArraysForWinner(23));
    }

    public function testFilterByMarkThrowsExceptionForIncorrectMark()
    {
        $this->expectException(InvalidMarkException::class);
        self::$board->filterByMark(4);
    }

    public function testFilterByMarkReturnsCorrectArrayForValidMark()
    {
        $unmarked = self::$board->filterByMark(0);
        $this->assertEquals(
            20,
            count($unmarked)
        );
        $marked = self::$board->filterByMark(1);
        $this->assertEquals(
            5,
            count($marked)
        );
    }

    public function testUnmarkedBoardValueCalculatesCorrectValue()
    {
        $this->assertEquals(
            223,
            self::$board->getUnmarkedBoardValue()
        );
    }
}