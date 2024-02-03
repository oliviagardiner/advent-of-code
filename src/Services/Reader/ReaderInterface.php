<?php declare(strict_types=1);

namespace App\Services\Reader;

interface ReaderInterface
{
    public function read($path);
}
