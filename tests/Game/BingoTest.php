<?php

use App\Exceptions\GameOverException;
use App\Game\Bingo\Bingo;
use App\Reader\BingoReader;
use App\Reader\BingoNumberReader;
use PHPUnit\Framework\TestCase;

class BingoTest extends TestCase
{
    public static Bingo $bingo;

    public static function setUpBeforeClass(): void
    {
        $bingoreader = new BingoReader(__DIR__ . '..\..\fixtures\day-4-testinput.txt');
        $numberreader = new BingoNumberReader(__DIR__ . '..\..\fixtures\day-4-testinput.txt');
        self::$bingo = new Bingo($bingoreader, $numberreader);
    }

    public function testDrawNumbeReturnsNextNumber()
    {
        $this->assertEquals(
            7,
            self::$bingo->drawNumber()
        );
        self::$bingo->incrementRound();
        $this->assertEquals(
            4,
            self::$bingo->drawNumber()
        );
    }

    public function testHasWinnerReturnsFalseForNoMark()
    {
        $this->assertFalse(self::$bingo->hasWinner());
    }

    public function testHasWinnerReturnsFalseForPartiallyMarked()
    {
        self::$bingo->checkNumberOnBoards(17);
        $this->assertFalse(self::$bingo->hasWinner());
    }

    public function testHasWinnerReturnsTrueForFullyMarked()
    {
        self::$bingo->checkNumberOnBoards(17);
        self::$bingo->checkNumberOnBoards(23);
        self::$bingo->checkNumberOnBoards(14);
        self::$bingo->checkNumberOnBoards(3);
        self::$bingo->checkNumberOnBoards(20);
        $this->assertTrue(self::$bingo->hasWinner());
    }

    public function testIncrementRoundThrowsExceptionForGameWon()
    {
        $this->expectException(GameOverException::class);
        self::$bingo->incrementRound();
    }

    public function testCalculateFinalScoreReturnsCorrectValue()
    {
        $this->assertEquals(
            892,
            self::$bingo->calculateFinalScore()
        );
    }

    public function testFullGameReturnsWinningBoardValue()
    {
        $bingoreader = new BingoReader(__DIR__ . '..\..\fixtures\day-4-testinput.txt');
        $numberreader = new BingoNumberReader(__DIR__ . '..\..\fixtures\day-4-testinput.txt');
        $bingo = new Bingo($bingoreader, $numberreader);
        $bingo->play();
        $this->assertEquals(
            4512,
            $bingo->calculateFinalScore()
        );
    }
}