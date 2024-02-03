<?php declare(strict_types=1);

namespace App\Services\LineParser;

class HighestPowerRGBParser extends HighestRGBParser
{
    public function parse(string $line): string
    {
        $this->resetQualtities();

        if ($this->isInputValid($line)) {
            $this->getHighestOfEachColor($line);
        }

        list($red, $green, $blue) = array_values($this->quantities);
        if ($red === 0 || $green === 0 || $blue === 0) {
            return '0';
        }
        
        return (string)($this->quantities['red'] * $this->quantities['green'] * $this->quantities['blue']);
    }
}
