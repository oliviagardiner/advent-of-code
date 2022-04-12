<?php

namespace App;

use App\InputReader\InputReaderInterface;
use App\Exceptions\NotFileException;
use App\Exceptions\IncorrectExtensionException;

class Sonar
{
    private array $lines;
    private int $previousitem;
    private int $currentitem;

    /**
     * @throws NotFileException
     * @throws IncorrectExtensionException
     */
    public function __construct(
        private InputReaderInterface $reader
    )
    {
        $this->lines = $this->reader->readLines();
    }

    public function countInclines(): int
    {
        return array_reduce($this->lines, function($carry, $item) {
            $this->setCurrentitem((int)$item);
            if ($this->isIncline()) $carry++;
            $this->setPreviousitem((int)$item);
            return $carry;
        }, 0);
    }

    public function setCurrentitem(int $item): void
    {
        $this->currentitem = $item;
    }

    public function setPreviousitem(int $item): void
    {
        $this->previousitem = $item;
    }

    public function isIncline(): bool
    {
        if (isset($this->previousitem)) {
            return $this->isCurrentGreater();
        } else {
            return false;
        }
    }

    public function isCurrentGreater(): bool
    {
        return $this->currentitem > $this->previousitem;
    }
}