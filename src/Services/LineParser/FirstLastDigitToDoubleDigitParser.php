<?php declare(strict_types=1);

namespace App\Services\LineParser;

class FirstLastDigitToDoubleDigitParser implements LineParserInterface
{
    public function parse(string $line): string
    {
        $numberString = preg_replace('/[A-Za-z]/', '', $line);
        if (($len = strlen($numberString)) <= 2) {
            return $len == 2 ? $numberString : $numberString . $numberString;
        }
        
        $numberList = mb_str_split($numberString);
        return array_shift($numberList) . end($numberList);
    }
}
