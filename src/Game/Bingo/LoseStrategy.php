<?php

namespace App\Game\Bingo;

class LoseStrategy implements BingoStrategy
{
    public function checkWinCondition(Bingo $bingo): bool
    {
        return $bingo->hasLoser();
    }
}