<?php declare(strict_types=1);

namespace App\Tests;

use App\SumProcessor;
use App\Exception\ParserNotSetException;
use App\Services\Reader\GenericReader;
use PHPUnit\Framework\TestCase;

class SumProcessorTest extends TestCase
{
    protected SumProcessor $sut;

    public function setUp(): void
    {
        $this->sut = new SumProcessor(new GenericReader());
    }

    public function testProcessThrowsExceptionIfNoParserSelected(): void
    {
        $this->expectException(ParserNotSetException::class);
        $this->sut->process(__DIR__ . '/fixtures/empty');
    }
}