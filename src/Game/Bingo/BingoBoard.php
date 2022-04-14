<?php

namespace App\Game\Bingo;

use App\Parser\BoardParser;
use App\Exceptions\InvalidNumberException;
use App\Exceptions\InvalidMarkException;
use App\Exceptions\NumberNotInBoardException;

class BingoBoard
{
    private array $numberPositions;
    private array $numberMarks;
    private array $rows;
    private array $columns;
    private bool $won = false;

    public function __construct(
        array $input
    ) {
        $this->parseInput($input);
    }

    private function parseInput(array $input): void
    {
        $this->rows = $input;
        $flat = array_merge(...array_values($input));
        $this->numberPositions = array_flip($flat);
        $this->numberMarks = array_fill_keys($flat, 0);
        
        foreach ($input as $key => $row) {
            foreach ($row as $nkey => $number) {
                $this->columns[$nkey][$key] = $number;
            }
        }
    }

    public function isWinner(int $number): bool
    {
        try {
            $this->isValidNumber($number);
            $this->markNumber($number);
            return $this->checkArraysForWinner($number);
        } catch (InvalidNumberException|NumberNotInBoardException $e) {
            return false;
        }
    }

    public function markWinner(): void
    {
        $this->won = true;
    }

    public function hasAlreadyWon(): bool
    {
        return $this->won;
    }

    /**
     * @throws InvalidNumberException
     * @throws NumberNotInBoardException
     */
    private function isValidNumber(int $number): bool
    {
        if ($number < 0) {
            throw new InvalidNumberException('Number cannot be less than 0.');
        } elseif (!isset($this->numberPositions[$number])) {
            throw new NumberNotInBoardException('The number is not present in this bingo board.');
        } else {
            return true;
        }
    }

    public function markNumber(int $number): void
    {
        $this->numberMarks[$number] = 1;
    }

    public function getRowByNumber(int $number): array
    {
        $position = $this->numberPositions[$number];
        $row = floor($position / 5);
        return array_values($this->rows[$row]);
    }

    public function getColumnByNumber(int $number): array
    {
        $position = $this->numberPositions[$number];
        $col = $position % 5;
        return $this->columns[$col];
    }

    public function getArrayValue(array $arr): int
    {
        return array_reduce($arr, function($carry, $item) {
            return $carry + $this->numberMarks[$item];
        }, 0);
    }

    public function checkWinCondition(array $arr): bool
    {
        return $this->getArrayValue($arr) === 5;
    }

    public function checkArraysForWinner(int $number): bool
    {
        $row = $this->getRowByNumber($number);
        $column = $this->getColumnByNumber($number);
        return $this->checkWinCondition($row) || $this->checkWinCondition($column);
    }

    public function getUnmarkedBoardValue(): int
    {
        $unmarked = $this->filterByMark(0);
        return array_sum(array_keys($unmarked));
    }

    /**
     * @throws InvalidMarkException
     */
    public function filterByMark(int $mark): array
    {
        if (in_array($mark, [0, 1])) {
            return array_filter($this->numberMarks, fn($item) => $item === $mark);
        } else {
            throw new InvalidMarkException('Mark can only be 1 or 0.');
        }
    }
}