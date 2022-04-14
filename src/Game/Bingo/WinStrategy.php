<?php

namespace App\Game\Bingo;

class WinStrategy implements BingoStrategy
{
    public function checkWinCondition(Bingo $bingo): bool
    {
        return $bingo->hasWinner();
    }

    public function pickBoard(Bingo $bingo): BingoBoard
    {
        return $bingo->getWinningBoard();
    }
}