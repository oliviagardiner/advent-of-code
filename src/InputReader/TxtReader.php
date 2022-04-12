<?php

namespace App\InputReader;

use App\Input\Input;
use App\Exceptions\NotFileException;
use App\Exceptions\IncorrectExtensionException;

class TxtReader implements InputReaderInterface
{
    /**
     * @throws NotFileException
     * @throws IncorrectExtensionException
     */
    public function __construct(
        private Input $input
    )
    {
        
    }

    public function readLines(): array
    {
        return file($this->input->getPath());
    }

    public function mapLinesToInteger(): array
    {
        return array_map(function($item) {
            return (int)$item;
        }, $this->readLines());
    }
}