<?php declare(strict_types=1);

namespace App;

use App\Exception\InputFormatIncorrectException;

class CubeGame extends SumProcessor
{
    private array $bag = [];

    public function getValidGameIdSum(string $bag, string $input): int
    {
        $this->bag = $this->rgbToArray($bag);
        $this->process($input);

        return $this->sum;
    }

    protected function processLine($line) : void
    {
        $gameResult = $this->parser->parse($line);

        if ($this->isGameValid($gameResult)) {
            $this->sum += $this->extractId($line);
        }
    }

    private function isGameValid(string $game): bool
    {
        $gameArray = $this->rgbToArray($game);

        return $this->bag['red'] >= $gameArray['red'] && $this->bag['green'] >= $gameArray['green'] && $this->bag['blue'] >= $gameArray['blue'];
    }

    private function extractId(string $line): int
    {
        $parts = explode(';', $line);
        return (int)str_replace('Game ', '', $parts[0]);
    }

    private function rgbToArray(string $input): array
    {
        if (!preg_match('/(\d+ (blue|red|green), ){0,}\d+ (blue|red|green)/', $input)) {
            throw new InputFormatIncorrectException("Input format incorrect, expected: % red, % green, % blue and received: $input.");
        }
        $parts = explode(' ', $input);
        return ['red' => (int)$parts[0], 'green' => (int)$parts[2], 'blue' => (int)$parts[4]];
    }
}
