<?php

namespace App;

use App\Reader\TxtReader;
use App\Exceptions\NotFileException;
use App\Exceptions\IncorrectExtensionException;

class Diagnostics
{
    private array $reportOriginal;
    private array $report;
    private array $groups;

    /**
     * @throws NotFileException
     * @throws IncorrectExtensionException
     */
    public function __construct(
        private TxtReader $reader
    )
    {
        $this->report = $this->reader->read();
        $this->reportOriginal = $this->report;
    }

    public function processReport(): void
    {
        foreach ($this->report as $line) {
            $digits = $this->processLine($line);
            $this->populateGroups($digits);
        }
    }

    public function checkForProcess()
    {
        if (empty($this->groups)) {
            $this->processReport();
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

    public function clearGroups(): void
    {
        $this->groups = [];
    }

    public static function getMostCommonBit(array $digits): int
    {
        return array_sum($digits) >= count($digits) / 2 ? 1 : 0;
    }

    public static function getLeastCommonBit(array $digits): int
    {
        return self::getMostCommonBit($digits) === 1 ? 0 : 1;
    }

    public function generateBinaryFromGroups(callable $callback): string
    {
        $this->checkForProcess();

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

    public function lineMatchesCriteria(string $line, int $position, int $criteria): bool
    {
        return (int)substr($line, $position, 1) === $criteria;
    }

    public function filterReportByCriteria(int $position, int $criteria): array
    {
        return array_values(array_filter($this->report, function($item) use ($position, $criteria) {
            return $this->lineMatchesCriteria($item, $position, $criteria);
        }));
    }

    public function reduceGroupsByCriteria(callable $callback): string
    {
        $this->checkForProcess();

        $position = 0;

        while (count($this->report) > 1 && $position < count($this->groups)) {
            $criteria = call_user_func($callback, $this->groups[$position]);
            $this->report = $this->filterReportByCriteria($position, $criteria);
            $this->clearGroups();
            $this->processReport();
            $position++;
        }

        return end($this->report);
    }

    public function computeOxygenGenRatingBinary(): string
    {
        return $this->reduceGroupsByCriteria('self::getMostCommonBit');
    }

    public function computeOxygenGenRating(): int
    {
        return bindec($this->computeOxygenGenRatingBinary());
    }

    public function computeCo2RatingBinary(): string
    {
        return $this->reduceGroupsByCriteria('self::getLeastCommonBit');
    }

    public function computeCo2Rating(): int
    {
        return bindec($this->computeCo2RatingBinary());
    }

    public function getLifeSupportRating(): int
    {
        $o2rating = $this->computeOxygenGenRating();
        $this->report = $this->reportOriginal;
        $co2rating = $this->computeCo2Rating();
        return $o2rating * $co2rating;
    }
}