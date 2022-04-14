<?php

namespace App\Reader;

class BingoNumberReader extends TxtReader
{
    public function read(): array
    {
        $lines = $this->readLines();
        return explode(',', array_shift($lines));
    }
}