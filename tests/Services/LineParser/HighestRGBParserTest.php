<?php declare(strict_types=1);

namespace App\Tests\LineParser;

use App\Services\LineParser\HighestRGBParser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class HighestRGBParserTest extends TestCase
{
    private HighestRGBParser $sut;

    public function setUp(): void
    {
        $this->sut = new HighestRGBParser();
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
            'expected' => '0 red, 0 green, 0 blue'
        ];

        yield 'Game 1' => [
            'input' => 'Game 1: 3 blue, 4 red; 1 red, 2 green, 6 blue; 2 green',
            'expected' => '4 red, 2 green, 6 blue'
        ];

        yield 'Game 2' => [
            'input' => 'Game 2: 1 blue, 2 green; 3 green, 4 blue, 1 red; 1 green, 1 blue',
            'expected' => '1 red, 3 green, 4 blue'
        ];

        yield 'Game 3' => [
            'input' => 'Game 3: 8 green, 6 blue, 20 red; 5 blue, 4 red, 13 green; 5 green, 1 red',
            'expected' => '20 red, 13 green, 6 blue'
        ];

        yield 'Game 4' => [
            'input' => 'Game 4: 1 green, 3 red, 6 blue; 3 green, 6 red; 3 green, 15 blue, 14 red',
            'expected' => '14 red, 3 green, 15 blue'
        ];

        yield 'Game 5' => [
            'input' => 'Game 5: 6 red, 1 blue, 3 green; 2 blue, 1 red, 2 green',
            'expected' => '6 red, 3 green, 2 blue'
        ];
    }
}
