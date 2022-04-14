<?php

namespace App\Reader;

class DiagnosticsReader extends TxtReader
{
    public function read(): array
    {
        return array_map(
            fn($line) => rtrim($line), 
            $this->readLines()
        );
    }
}