<?php declare(strict_types=1);

namespace App\Tests;

use App\Calibrator;
use App\Services\LineCorrector\FirstLastMapReplacer;
use App\Services\LineParser\FirstLastDigitToDoubleDigitParser;
use App\Services\Reader\GenericReader;
use PHPUnit\Framework\TestCase;
use App\Exception\ParserNotSetException;
use PHPUnit\Framework\Attributes\DataProvider;

class CalibratorTest extends TestCase
{
    private string $path = __DIR__ . '/fixtures/day1';

    public function testCalibrateWillThrowExceptionIfNoParserSelected(): void
    {
        $sut = new Calibrator(new GenericReader());
        $this->expectException(ParserNotSetException::class);
        $sum = $sut->calibrate($this->path);
    }

    #[DataProvider('calibrateProvider')]
    public function testCalibrateWillReturnSumOfFirstAndLastLineDigits(string $path, int $expected): void
    {
        $sut = new Calibrator(new GenericReader());
        $sut->setParser(new FirstLastDigitToDoubleDigitParser());
        $sum = $sut->calibrate($path);
        $this->assertSame($expected, $sum);
    }

    public static function calibrateProvider(): iterable
    {
        yield 'Short file' => [
            'path' => __DIR__ . '/fixtures/day1short',
            'expected' => 142
        ];

        yield 'Long file' => [
            'path' => __DIR__ . '/fixtures/day1',
            'expected' => 54605
        ];
    }

    #[DataProvider('calibrateCorrectedProvider')]
    public function testCalibrateApplyingLineCorrectorToReaderrWillReturnSumOfFirstAndLastDigitsOrNumericStrings(string $path, int $expected): void
    {
        $reader = new GenericReader();
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
        $reader->applyLineCorrector(new FirstLastMapReplacer(), $map);
        $sut = new Calibrator($reader);
        $sut->setParser(new FirstLastDigitToDoubleDigitParser());
        $sum = $sut->calibrate($path);
        $this->assertSame($expected, $sum);
    }

    public static function calibrateCorrectedProvider(): iterable
    {
        yield 'Short file' => [
            'path' => __DIR__ . '/fixtures/day1shortcorrected',
            'expected' => 281
        ];

        yield 'Long file' => [
            'path' => __DIR__ . '/fixtures/day1',
            'expected' => 55429
        ];
    }
}
