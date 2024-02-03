<?php declare(strict_types=1);

namespace App\Services\LineParser;

class HighestRGBParser implements LineParserInterface
{
    protected array $quantities = [];

    public function parse(string $line): string
    {
        $this->resetQualtities();

        if ($this->isInputValid($line)) {
            $this->getHighestOfEachColor($line);
        }
        
        $pattern = '%s red, %s green, %s blue';
        return sprintf($pattern, ...array_values($this->quantities));
    }

    protected function resetQualtities(): void
    {
        $this->quantities = [
            'red' => 0,
            'green' => 0,
            'blue' => 0
        ];
    }

    protected function isInputValid(string $input): bool
    {
        return !empty($input) && preg_match('/Game \d{1,3}: (\d{1,3} (blue|red|green), ){0,}\d{1,3} (blue|red|green); (\d{1,3} (blue|red|green), ){0,}\d{1,3} (blue|red|green); (\d{1,3} (blue|red|green), ){0,}\d{1,3} (blue|red|green)/', $input) !== false;
    }

    protected function getHighestOfEachColor(string $line): void
    {
        list($game, $draws) = explode(':', $line, 2);
        $sets = explode(';', $draws);

        foreach ($sets as $set) {
            $setPieces = explode(',', trim($set));
            foreach ($setPieces as $setPiece) {
                list($qty, $color) = explode(' ', trim($setPiece), 2);
                if ($qty > $this->quantities[$color]) {
                    $this->quantities[$color] = $qty;
                }
            }
        }
    }
}
