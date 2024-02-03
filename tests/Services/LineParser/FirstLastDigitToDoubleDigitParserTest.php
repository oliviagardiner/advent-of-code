<?php declare(strict_types=1);

namespace App\Tests\LineParser;

use App\Services\LineParser\FirstLastDigitToDoubleDigitParser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class FirstLastDigitToDoubleDigitParserTest extends TestCase
{
    private FirstLastDigitToDoubleDigitParser $sut;

    public function setUp(): void
    {
        $this->sut = new FirstLastDigitToDoubleDigitParser();
    }

    public function testParseDuplicatesNumberIfLineHasOnlyOneDigit(): void
    {
        $result = $this->sut->parse('test1test');
        $this->assertSame('11', $result);
    }

    public function testParseReturnsEmptyStringIfLineHasZeroDigits(): void
    {
        $result = $this->sut->parse('teststringwithnodigits');
        $this->assertSame('', $result);
    }

    #[DataProvider('inputProvider')]
    public function testParseWithTwoOrMoreNumbers(string $input, string $expected): void
    {
        $result = $this->sut->parse($input);
        $this->assertSame($expected, $result);
    }

    public static function inputProvider(): iterable
    {
        yield 'Exactly two numbers' => [
            'input' => '12',
            'expected' => '12'
        ];

        yield 'Exactly two of the same number' => [
            'input' => '22',
            'expected' => '22'
        ];
        
        yield 'Exactly two numbers, spread out' => [
            'input' => 'test1te2st',
            'expected' => '12'
        ];

        yield 'The same number at the beginning and the end' => [
            'input' => '1test1',
            'expected' => '11'
        ];

        yield 'More than two numbers, spread out' => [
            'input' => 'test1te2345st6',
            'expected' => '16'
        ];

        yield 'Only different numbers' => [
            'input' => '123456789',
            'expected' => '19'
        ];

        yield 'Only the same numbers' => [
            'input' => '9999999999',
            'expected' => '99'
        ];
    }
}
