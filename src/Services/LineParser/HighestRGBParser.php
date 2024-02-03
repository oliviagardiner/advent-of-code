<?php declare(strict_types=1);

namespace App\Services\LineParser;

class HighestRGBParser implements LineParserInterface
{
    public function parse(string $line): string
    {
        $pattern = '%s red, %s green, %s blue';
        $quantities = [
            'red' => 0,
            'green' => 0,
            'blue' => 0
        ];

        list($game, $draws) = explode(':', $line, 2);
        $sets = explode(';', $draws);

        foreach ($sets as $set) {
            $setPieces = explode(',', trim($set));
            foreach ($setPieces as $setPiece) {
                list($qty, $color) = explode(' ', trim($setPiece), 2);
                if ($qty > $quantities[$color]) {
                    $quantities[$color] = $qty;
                }
            }
        }
        return sprintf($pattern, ...array_values($quantities));
    }
}
