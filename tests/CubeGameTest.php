<?php declare(strict_types=1);

namespace App\Tests;

use App\CubeGame;
use App\Services\LineParser\HighestRGBParser;
use App\Services\LineParser\HighestPowerRGBParser;
use App\Services\Reader\GenericReader;
use App\Exception\ParserNotSetException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class CubeGameTest extends TestCase
{
    private CubeGame $sut;

    public function setUp(): void
    {
        $this->sut = new CubeGame(new GenericReader);
    }

    public function testProcessThrowsExceptionIfNoParserSelected(): void
    {
        $this->expectException(ParserNotSetException::class);
        $this->sut->process(__DIR__ . '/fixtures/empty');
    }

    #[DataProvider('inputProvider')]
    public function testGetValidGameIdSum(string $path, int $expected): void
    {
        $bag = '12 red, 13 green, 14 blue';
        $parser = new HighestRGBParser();
        $this->sut->setParser($parser);
        $result = $this->sut->getValidGameIdSum($bag, $path);
        $this->assertSame($expected, $result);
    }

    public static function inputProvider(): iterable
    {
        yield 'Short input' => [
            'path' => __DIR__ . '/fixtures/day2short',
            'expected' => 8
        ];

        yield 'Long input' => [
            'path' => __DIR__ . '/fixtures/day2',
            'expected' => 2105
        ];
    }

    #[DataProvider('inputProviderPower')]
    public function testGetTotalGamePower(string $path, int $expected): void
    {
        $parser = new HighestPowerRGBParser();
        $this->sut->setParser($parser);
        $result = $this->sut->getGamePowerSum($path);
        $this->assertSame($expected, $result);
    }

    public static function inputProviderPower(): iterable
    {
        yield 'Short input' => [
            'path' => __DIR__ . '/fixtures/day2short',
            'expected' => 2286
        ];

        yield 'Long input' => [
            'path' => __DIR__ . '/fixtures/day2',
            'expected' => 72422
        ];
    }
}
