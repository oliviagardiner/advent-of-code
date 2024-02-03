<?php declare(strict_types=1);

namespace App\Services\LineCorrector;

interface LineCorrectorInterface
{
    public function correct(string $line, array $rule): string;
}
