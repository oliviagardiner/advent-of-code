<?php

namespace App\Game\Bingo;

interface BingoStrategy
{
    public function checkWinCondition(Bingo $bingo): bool;
}