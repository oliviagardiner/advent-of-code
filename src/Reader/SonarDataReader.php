<?php

namespace App\Reader;

class SonarDataReader extends TxtReader
{
    public function read(): array
    {
        return array_map(function($item) {
            return (int)rtrim($item);
        }, $this->readLines());
    }
}