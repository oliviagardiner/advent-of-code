<?php declare(strict_types=1);

namespace App\Tests;

use App\CubeGame;
use App\Services\LineParser\HighestRGBParser;
use App\Services\Reader\GenericReader;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class CubeGameTest extends TestCase
{
    #[DataProvider('inputProvider')]
    public function testGetValidGameIdSum(string $path, int $expected): void
    {
        $bag = '12 red, 13 green, 14 blue';
        $reader = new GenericReader();
        $sut = new CubeGame($reader);
        $parser = new HighestRGBParser();
        $sut->setParser($parser);
        $result = $sut->getValidGameIdSum($bag, $path);
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
}