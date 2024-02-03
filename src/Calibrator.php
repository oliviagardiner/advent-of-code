<?php declare(strict_types=1);

namespace App;

class Calibrator extends SumProcessor
{
    public function calibrate($input): int
    {
        $this->process($input);

        return $this->sum;
    }
}
