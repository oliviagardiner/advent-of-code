<?php

namespace App;

use App\InputReader\InputReaderInterface;
use App\Exceptions\NotFileException;
use App\Exceptions\IncorrectExtensionException;

class Diagnostics
{
    private array $report;
    private array $groups;

    /**
     * @throws NotFileException
     * @throws IncorrectExtensionException
     */
    public function __construct(
        private InputReaderInterface $reader
    )
    {
        $this->report = $this->reader->readLines();
    }

    public function processReport(): void
    {
        foreach ($this->report as $line) {
            $digits = $this->processLine($line);
            $this->populateGroups($digits);
        }
    }

    public function processLine(string $line): array
    {
        return str_split(trim($line, '\n'));
    }

    public function populateGroups(array $digits): void
    {
        foreach ($digits as $key => $digit) {
            if (is_numeric($digit)) $this->groups[$key][] = $digit;
        }
    }

    public static function getMostCommonBit(array $digits): int
    {
        return array_sum($digits) > count($digits) / 2 ? 1 : 0;
    }

    public static function getLeastCommonBit(array $digits): int
    {
        return self::getMostCommonBit($digits) === 1 ? 0 : 1;
    }

    public function generateBinaryFromGroups(callable $callback): string
    {
        if (empty($this->groups)) {
            $this->processReport();
        }

        $binary = '';
        foreach ($this->groups as $digits) {
            $binary .= call_user_func($callback, $digits);
        }

        return $binary;
    }

    public function getMostCommonBinaryFromGroups(): string
    {
        return $this->generateBinaryFromGroups('self::getMostCommonBit');
    }

    public function computeGammaRate(): int|float
    {
        return bindec($this->getMostCommonBinaryFromGroups());
    }

    public function getLeastCommonBinaryFromGroups(): string
    {
        return $this->generateBinaryFromGroups('self::getLeastCommonBit');
    }

    public function computeEpsilonRate(): int|float
    {
        return bindec($this->getLeastCommonBinaryFromGroups());
    }

    public function getPowerConsumption(): int
    {
        return $this->computeGammaRate() * $this->computeEpsilonRate();
    }
}