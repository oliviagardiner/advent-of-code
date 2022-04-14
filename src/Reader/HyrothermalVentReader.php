<?php

namespace App\Reader;

class HydrothermalVentReader extends TxtReader
{
    public function read(): array
    {
        return array_map(
            fn($line) => $this->getPairs($line), 
            $this->readLines()
        );
    }

    public function getPairs(string $line): array
    {
        $pairs = explode(' -> ', rtrim($line));
        return array_map(
            fn($pair) => $this->getCoordinates($pair),
            $pairs
        );
    }

    public function getCoordinates(string $pair): array
    {
        return array_map(
            fn($i) => (int)$i,
            explode(',', $pair)
        );
    }
}