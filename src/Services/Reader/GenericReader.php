<?php declare(strict_types=1);

namespace App\Services\Reader;

use App\Services\LineCorrector\LineCorrectorInterface;
use App\Services\Reader\Exception\FileNotExistException;
use App\Services\Reader\Exception\EndOfFileException;

class GenericReader implements ReaderInterface
{
    private $handle = null;

    private ?LineCorrectorInterface $corrector;

    private ?array $rule;

    /**
     * @throws FileNotExistException
     * @throws EndOfFileException
     */
    public function read($path)
    {
        if (!$this->isInputValid($path)) {
            throw new FileNotExistException("$path is not a valid file path.");
        } 

        $this->setFile($path);
            
        if ($this->handle && !$this->handle->eof()) {
            $line = trim($this->handle->fgets());
            return $this->hasLineCorrector() ? $this->corrector->correct($line, $this->rule) : $line;
        }

        throw new EndOfFileException("Finished reading file at $path.");
    }

    private function isInputValid($input): bool
    {
        return !empty($input) && is_file($input);
    }

    /**
     * @throws \RunTimeException
     */
    private function setFile(string $path): void
    {
        if (!$this->handle) {
            $this->handle = new \SplFileObject($path);
        }
    }

    public function applyLineCorrector(LineCorrectorInterface $corrector, array $rule): void
    {
        $this->corrector = $corrector;
        $this->rule = $rule;
    }

    private function hasLineCorrector(): bool
    {
        return isset($this->corrector) && !empty($this->rule);
    }
}
