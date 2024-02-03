<?php declare(strict_types=1);

namespace App\Tests\LineParser;

use App\Services\LineParser\HighestPowerRGBParser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class HighestPowerRGBParserTest extends TestCase
{
    private HighestPowerRGBParser $sut;

    public function setUp(): void
    {
        $this->sut = new HighestPowerRGBParser();
    }

    #[DataProvider('lineProvider')]
    public function testParseReturnsHighestNumberDrawnForColors(string $input, string $expected): void
    {
        $result = $this->sut->parse($input);
        $this->assertSame($expected, $result);
    }

    public static function lineProvider(): iterable
    {
        yield 'Empty game' => [
            'input' => '',
            'expected' => '0'
        ];

        yield 'Game has 0 as highest value for a color' => [
            'input' => 'Game 1: 3 blue, 4 red; 1 red, 6 blue; 2 blue',
            'expected' => '0'
        ];

        yield 'Game 1' => [
            'input' => 'Game 1: 3 blue, 4 red; 1 red, 2 green, 6 blue; 2 green',
            'expected' => '48'
        ];

        yield 'Game 2' => [
            'input' => 'Game 2: 1 blue, 2 green; 3 green, 4 blue, 1 red; 1 green, 1 blue',
            'expected' => '12'
        ];

        yield 'Game 3' => [
            'input' => 'Game 3: 8 green, 6 blue, 20 red; 5 blue, 4 red, 13 green; 5 green, 1 red',
            'expected' => '1560'
        ];

        yield 'Game 4' => [
            'input' => 'Game 4: 1 green, 3 red, 6 blue; 3 green, 6 red; 3 green, 15 blue, 14 red',
            'expected' => '630'
        ];

        yield 'Game 5' => [
            'input' => 'Game 5: 6 red, 1 blue, 3 green; 2 blue, 1 red, 2 green',
            'expected' => '36'
        ];
    }
}
