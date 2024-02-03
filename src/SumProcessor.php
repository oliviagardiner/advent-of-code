<?php declare(strict_types=1);

namespace App;

use App\AbstractInputProcessor;
use App\Exception\ParserNotSetException;

abstract class SumProcessor extends AbstractInputProcessor
{
    protected int $sum = 0;

    protected function checkProcessingRequirements(): void
    {
        if (!isset($this->parser)) {
            throw new ParserNotSetException('A parser must be selected before processing.');
        }
    }

    protected function processLine($line): void
    {
        $this->sum += (int)$this->parser->parse($line);
    }

    
}
