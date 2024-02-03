<?php declare(strict_types=1);

namespace App\Services\LineParser;

interface LineParserInterface
{
    public function parse(string $line): string;
}
