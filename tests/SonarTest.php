<?php

use App\Input\TxtInput;
use App\InputReader\TxtReader;
use App\Sonar;
use App\Exceptions\NotFileException;
use App\Exceptions\IncorrectExtensionException;
use PHPUnit\Framework\TestCase;

//./vendor/bin/phpunit tests
class SonarTest extends TestCase
{
    private static Sonar $sonar;

    /**
     * @throws NotFileException
     * @throws IncorrectExtensionException
     */
    public static function setUpBeforeClass(): void
    {
        $input = new TxtInput(__DIR__ . '\fixtures\day-1-testinput.txt', 'txt');
        $reader = new TxtReader($input);
        self::$sonar = new Sonar($reader);
    }

    public function testCanReturnCorrectInclineCountFromFile()
    {
        $this->assertEquals(
            7,
            self::$sonar->countInclines()
        );
    }

    /**
     * @throws TypeError
     */
    public function testThrowsTypeErrorIfCurrentitemNotInteger()
    {
        $this->expectException(TypeError::class);

        self::$sonar->setCurrentitem('foo');
    }

    /**
     * @throws TypeError
     */
    public function testThrowsTypeErrorIfPreviousitemNotInteger()
    {
        $this->expectException(TypeError::class);

        self::$sonar->setPreviousitem('bar');
    }

    public function testIsInclineReturnsTrueIfCurrentIsGreater()
    {
        self::$sonar->setCurrentitem(100);
        self::$sonar->setPreviousitem(50);

        $this->assertTrue(self::$sonar->isIncline());
    }

    public function testIsInclineReturnsFalseIfPreviousIsGreater()
    {
        self::$sonar->setCurrentitem(50);
        self::$sonar->setPreviousitem(100);

        $this->assertFalse(self::$sonar->isIncline());
    }

    public function testIsInclineReturnsFalseIfPreviousNotSet()
    {
        self::$sonar->setCurrentitem(100);

        $this->assertFalse(self::$sonar->isIncline());
    }
}