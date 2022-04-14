<?php

namespace App\Reader;

class NavigationReader extends TxtReader
{
    public function read(): array
    {
        return $this->readLines();
    }
}