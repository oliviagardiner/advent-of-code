<?php

namespace App\InputReader;

use App\Input\Input;
use App\Exceptions\NotFileException;
use App\Exceptions\IncorrectExtensionException;

class TxtReader implements InputReaderInterface
{
    private Input $input;

    /**
     * @throws NotFileException
     * @throws IncorrectExtensionException
     */
    public function setInput(Input $input)
    {
        $this->input = $input;
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