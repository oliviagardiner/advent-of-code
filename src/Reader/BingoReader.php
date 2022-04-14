<?php

namespace App\Reader;

class BingoReader extends TxtReader
{
    public function read(): array
    {
        $lines = $this->getFileLines();
        array_shift($lines);
        return $lines;
    }

    public function getFileLines(): array
    {
        return array_map(
            fn($line) => array_values($this->turnLineIntoArray($line)), 
            array_filter($this->readLines(), 'trim')
        );
    }

    public function turnLineIntoArray(string $line): array
    {
        $cleanline = preg_split('/\s+/', $line);
        $cleanline = array_filter($cleanline, fn($elem) => $elem !== '');
        return array_map(
            fn($elem) => (int)$elem,
            $cleanline
        );
    }
}