<?php

use App\Exceptions\MissingStrategyException;
use App\Game\Bingo\Bingo;
use App\Game\Bingo\LoseStrategy;
use App\Game\Bingo\WinStrategy;
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

    public function testCalculateFinalScoreReturnsCorrectValue()
    {
        $strategy = new WinStrategy();
        self::$bingo->setStrategy($strategy);
        $this->assertEquals(
            892,
            self::$bingo->calculateFinalScore()
        );
    }

    public function testPlayThrowsExceptionIfStrategyNotSet()
    {
        $this->expectException(MissingStrategyException::class);
        self::$bingo->reset();
        self::$bingo->play();
    }

    public function testFullGameReturnsWinningBoardValue()
    {
        self::$bingo->reset();
        $strategy = new WinStrategy();
        self::$bingo->setStrategy($strategy);
        self::$bingo->play();
        $this->assertEquals(
            24,
            self::$bingo->drawNumber()
        );
        $this->assertEquals(
            4512,
            self::$bingo->calculateFinalScore()
        );
    }

    public function testResetReturnsPropertiesToDefault()
    {
        self::$bingo->reset();
        $this->assertEquals(
            7,
            self::$bingo->drawNumber()
        );
        $this->assertFalse(self::$bingo->hasWinner());
    }

    public function testLostReturnsLosingBoardValue()
    {
        self::$bingo->reset();
        $strategy = new LoseStrategy();
        self::$bingo->setStrategy($strategy);
        self::$bingo->play();
        $this->assertEquals(
            13,
            self::$bingo->drawNumber()
        );
        $this->assertEquals(
            1924,
            self::$bingo->calculateFinalScore()
        );
    }
}