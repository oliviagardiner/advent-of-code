<?php

namespace App\Game\Bingo;

use App\Game\Game;
use App\Reader\TxtReader;
use App\Exceptions\GameOverException;

class Bingo implements Game
{
    private int $round;
    private array $boards;
    private array $numbers;
    private BingoBoard $winningBoard;

    public function __construct(
        private TxtReader $bingoReader,
        private TxtReader $numberReader
    )
    {
        $this->round = 0;
        $this->numbers = $numberReader->read();
        $input = $bingoReader->read();
        for ($key = 0; $key < count($input);$key += 5) {
            $this->boards[] = new BingoBoard(array_slice($input, $key, 5));
        }
    }

    public function play()
    {
        while (!$this->hasWinner()) {
            $this->checkNumberOnBoards($this->drawNumber());
            $this->goToNextRound();
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
            if ($board->isWinner($number)) {
                $this->winningBoard = $board;
                break;
            }
        }
    }

    public function hasWinner(): bool
    {
        return isset($this->winningBoard);
    }

    public function announceWinner()
    {
        if ($this->hasWinner()) {
            $score = $this->calculateFinalScore();
            echo "You won the game in round {$this->round}. Your final score is {$score}.".PHP_EOL;
        } else {
            echo "No winners, you broke the game?".PHP_EOL;
        }
    }

    public function calculateFinalScore(): int
    {
        return $this->winningBoard->getUnmarkedBoardValue() * $this->drawNumber();
    }

    /**
     * @throws GameOverException
     */
    public function incrementRound(): void
    {
        if ($this->hasWinner()) {
            throw new GameOverException('Someone already won the game.');
        } elseif (!isset($this->numbers[$this->round + 1])) {
            throw new GameOverException('No more rounds left.');
        }
        $this->round++;
    }

    public function goToNextRound()
    {
        try {
            $this->incrementRound();
            $this->play();
        } catch (GameOverException $e) {
            //
        }
    }
}