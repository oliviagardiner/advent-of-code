<?php declare(strict_types=1);

namespace App\Tests\Services\Reader;

use App\Services\Reader\GenericReader;
use App\Services\Reader\Exception\FileNotExistException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class GenericReaderTest extends TestCase
{
    private GenericReader $sut;

    public function setUp(): void
    {
        $this->sut = new GenericReader();
    }

    #[DataProvider('invalidInputProvider')]
    public function testReadThrowsExceptionIfInputIsInvalid($input): void
    {
        $this->expectException(FileNotExistException::class);
        $this->sut->read($input);
    }

    public static function invalidInputProvider(): iterable
    {
        yield 'Input is empty string' => [
            'input' => ''
        ];

        yield 'Input is false' => [
            'input' => false
        ];

        yield 'Input is null' => [
            'input' => null
        ];

        yield 'Input is non-existent path' => [
            'input' => 'thisdoesnotexist'
        ];
    }

    public function testReadReturnsFirstLineOfInputFile(): void
    {
        $line = $this->sut->read(__DIR__ . '/../../fixtures/oneline');
        $this->assertSame('testline', $line);
    }

    public function testReadReturnsEmptyStringForEmptyFile(): void
    {
        $line = $this->sut->read(__DIR__ . '/../../fixtures/empty');
        $this->assertSame('', $line);
    }
}
