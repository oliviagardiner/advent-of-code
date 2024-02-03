<?php declare(strict_types=1);

namespace App\Tests\Services\LineCorrector;

use App\Services\LineCorrector\FirstLastMapReplacer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class FirstLastMapReplacerTest extends TestCase
{
    private FirstLastMapReplacer $sut;

    public function setUp(): void
    {
        $this->sut = new FirstLastMapReplacer();
    }

    #[DataProvider('inputAndMapProvider')]
    public function testReplaceChangesMapWords(string $input, string $expected): void
    {
        $map = [
            'one' => 1,
            'two' => 2,
            'three' => 3,
            'four' => 4,
            'five' => 5,
            'six' => 6,
            'seven' => 7,
            'eight' => 8,
            'nine' => 9
        ];
        $result = $this->sut->correct($input, $map);
        $this->assertSame($expected, $result);
    }

    public static function inputAndMapProvider(): iterable
    {
        yield 'One number string at the beginning' => [
            'input' => 'twovgtprdzcjjzkq3ffsbcblnpq',
            'expected' => '2vgtprdzcjjzkq3ffsbcblnpq'
        ];

        yield 'One number string in the middle' => [
            'input' => 'xgmqjone7j',
            'expected' => 'xgmqj17j'
        ];

        yield 'Multiple number strings and one digit' => [
            'input' => 'fourseveneighttdgghnfive7pchxddgggcq',
            'expected' => '4seveneighttdgghn57pchxddgggcq'
        ];

        yield 'Multiple number strings and multiple digits' => [
            'input' => 'two8sixbmrmqzrrb1seven',
            'expected' => '28sixbmrmqzrrb17'
        ];
        
        yield 'Number strings at the beginning and end of the string' => [
            'input' => 'two1nine',
            'expected' => '219'
        ];

        yield 'Only number strings' => [
            'input' => 'eightwothree',
            'expected' => '8wo3'
        ];

        yield 'Two number strings in the middle of the string' => [
            'input' => 'abcone2threexyz',
            'expected' => 'abc123xyz'
        ];

        yield 'Overlaps and numeric string at the end' => [
            'input' => 'xtwone3four',
            'expected' => 'x2ne34'
        ];

        yield 'Overlaps and numeric string at the middle' => [
            'input' => '4nineeightseven2',
            'expected' => '49eight72'
        ];

        yield 'Overlaps and no additional numeric strings' => [
            'input' => 'zoneight234',
            'expected' => 'z18234'
        ]; // !!! instructions were unclear about the rules here

        yield 'Only overlapping numeric strings' => [
            'input' => 'eighthree',
            'expected' => '83'
        ]; // !!! instructions were unclear about the rules here

        yield 'Numeric strings that are not in the map' => [
            'input' => '7pqrstsixteen',
            'expected' => '7pqrst6teen'
        ];

        yield 'The same numeric string at the beginning and at the end' => [
            'input' => 'fivetwocrhmvxqkvbeightfive1qzcxvds',
            'expected' => '5twocrhmvxqkvbeight51qzcxvds'
        ];

        yield 'The same numeric string at random places' => [
            'input' => 'kdzrjbh2txzz5hbone96one',
            'expected' => 'kdzrjbh2txzz5hb1961'
        ];

        yield 'Only the same numeric string' => [
            'input' => 'oneoneoneoneoneoneoneone',
            'expected' => '1oneoneoneoneoneone1'
        ];
    }
}
