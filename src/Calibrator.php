<?php declare(strict_types=1);

namespace App;

use App\Services\LineParser\LineParserInterface;
use App\Services\Reader\ReaderInterface;
use App\Services\Reader\Exception\EndOfFileException;
use App\Exception\ParserNotSetException;

class Calibrator
{
    private int $sum = 0;

    private ?LineParserInterface $parser;

    public function __construct(private ReaderInterface $reader) {}

    public function calibrate($input): int
    {
        if (!isset($this->parser)) {
            throw new ParserNotSetException('A parser must be selected before processing.');
        }

        try {
            while ($line = $this->reader->read($input)) {
                $this->sum += (int)$this->parser->parse($line);
            }
        } catch (EndOfFileException $e) {
            //
        } finally {
            return $this->sum;
        }
    }

    public function setParser(LineParserInterface $parser): void
    {
        $this->parser = $parser;
    }
}
