<?php

namespace App\Input;

use App\Exceptions\IncorrectExtensionException;
use App\Exceptions\NotFileException;

class Input
{
    public function __construct(
        protected string $path,
        protected string $extension
    )
    {
        if ($this->isNotFile()) {
            throw new NotFileException('The provided path does not point to a file: '.$this->path);
        }
        if ($this->fileIsNotType()) {
            throw new IncorrectExtensionException('The provided path does not match the specified extension.');
        }
    }

    private function isNotFile(): bool
    {
        return !is_file($this->path);
    }

    private function fileIsNotType(): bool
    {
        return pathinfo($this->path, PATHINFO_EXTENSION) !== $this->extension;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}