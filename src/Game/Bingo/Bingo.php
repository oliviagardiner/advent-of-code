<?php

namespace App\Game\Bingo;

use App\Game\Game;
use App\Reader\TxtReader;
use App\Exceptions\GameOverException;
use App\Exceptions\MissingStrategyException;

class Bingo implements Game
{
    protected int $round;
    private array $boards;
    private array $numbers;
    public int $winnerCount;
    private BingoBoard $winningBoard;
    private BingoBoard $losingBoard;
    private BingoStrategy $strategy;

    /**
     * @throws NotFileException
     * @throws IncorrectExtensionException
     */
    public function __construct(
        private TxtReader $bingoReader,
        private TxtReader $numberReader
    )
    {
        $this->reset();
    }

    public function reset()
    {
        $this->round = 0;
        unset($this->boards);
        $this->numbers = $this->numberReader->read();
        $this->winnerCount = 0;
        unset($this->winningBoard);
        unset($this->losingBoard);
        unset($this->strategy);

        $input = $this->bingoReader->read();
        for ($key = 0; $key < count($input);$key += 5) {
            $this->boards[] = new BingoBoard(array_slice($input, $key, 5));
        }
    }

    public function setStrategy(BingoStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @throws MissingStrategyException
     */
    public function play()
    {
        if (!isset($this->strategy)) {
            throw new MissingStrategyException('You must first set your strategy.');
        }
        $this->checkNumberOnBoards($this->drawNumber());

        while (!$this->strategy->checkWinCondition($this)) {
            try {
                $this->incrementRound();
                $this->checkNumberOnBoards($this->drawNumber());
            } catch (GameOverException $e) {
                break;
            }
        }
    }

    public function drawNumber(): int
    {
        return $this->numbers[$this->round];
    }

    public function checkNumberOnBoards(int $number): void
    {
        /**
         * @var BingoBoard $board
         */
        foreach ($this->boards as $board) {
            if ($board->hasAlreadyWon()) {
                continue;
            } elseif ($board->isWinner($number)) {
                $this->setWinner($board);
            }
        }
    }

    public function setWinner(BingoBoard $board): void
    {
        $board->markWinner();
        $this->winnerCount++;
        if (!$this->hasWinner()) $this->winningBoard = $board;
        if ($this->winnerCount === count($this->boards)) $this->losingBoard = $board;
    }

    public function hasWinner(): bool
    {
        return isset($this->winningBoard);
    }

    public function hasLoser(): bool
    {
        return isset($this->losingBoard);
    }

    public function announceResult()
    {
        if ($this->strategy->checkWinCondition($this)) {
            $score = $this->calculateFinalScore();
            echo "You finished the game in round {$this->round}. Your final score is {$score}.".PHP_EOL;
        } else {
            echo "The game is not over yet.".PHP_EOL;
        }
    }

    public function calculateFinalScore(): int
    {
        $board = $this->strategy->pickBoard($this);
        return $board->getUnmarkedBoardValue() * $this->drawNumber();
    }

    /**
     * @throws GameOverException
     */
    public function incrementRound(): void
    {
        if (!isset($this->numbers[$this->round + 1])) {
            throw new GameOverException('No more rounds left.');
        }
        $this->round++;
    }

    public function getWinningBoard(): BingoBoard
    {
        return $this->winningBoard;
    }

    public function getLosingBoard(): BingoBoard
    {
        return $this->losingBoard;
    }
}