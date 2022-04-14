<?php

namespace App\Reader;

use App\Exceptions\IncorrectExtensionException;
use App\Exceptions\NotFileException;

abstract class TxtReader
{
    private const EXTENSION = 'txt';

    /**
     * @throws NotFileException
     * @throws IncorrectExtensionException
     */
    public function __construct(
        protected string $path
    ) {
        $this->checkIsFile();
        $this->checkExtension();
    }

    public abstract function read(): array;

    public function readLines(): array
    {
        return file($this->path);
    }

    /**
     * @throws NotFileException
     */
    private function checkIsFile()
    {
        if (!is_file($this->path)) {
            throw new NotFileException("The provided path does not point to a file: {$this->path}");
        }
    }

    /**
     * @throws IncorrectExtensionException
     */
    private function checkExtension()
    {
        if (pathinfo($this->path, PATHINFO_EXTENSION) !== self::EXTENSION) {
            throw new IncorrectExtensionException("The provided path does not match the specified extension.");
        }
    }
}