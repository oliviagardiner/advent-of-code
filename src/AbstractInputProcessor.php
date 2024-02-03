<?php declare(strict_types=1);

namespace App;

use App\Services\LineParser\LineParserInterface;
use App\Services\Reader\ReaderInterface;
use App\Services\Reader\Exception\EndOfFileException;

abstract class AbstractInputProcessor
{
    protected ?LineParserInterface $parser;

    public function __construct(
        protected ReaderInterface $reader
    ) {}

    abstract protected function checkProcessingRequirements();

    abstract protected function processLine($line);


    public function process(string $input)
    {
        $this->checkProcessingRequirements();

        try {
            while ($line = $this->reader->read($input)) {
                $this->processLine($line);
            }
        } catch (EndOfFileException $e) {
            //
        }
    }

    public function setParser(LineParserInterface $parser): void
    {
        $this->parser = $parser;
    }
}
