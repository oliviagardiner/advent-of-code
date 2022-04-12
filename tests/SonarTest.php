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
    /**
     * @throws NotFileException
     * @throws IncorrectExtensionException
     */
    public static function getNewSonarWithTestInput(string $path): Sonar
    {
        $testsonardata = new TxtInput(__DIR__ . $path, 'txt');
        $testreader = new TxtReader();
        $testreader->setInput($testsonardata);
        return new Sonar($testreader);
    }

    public function testCanReturnCorrectInclineCountFromFile()
    {
        $sonar = self::getNewSonarWithTestInput('\fixtures\day-1-testinput.txt');
        $this->assertEquals(
            7,
            $sonar->countInclines()
        );
    }

    public function testCanReturnCorrectInclineCountFromFile2()
    {
        $sonar = self::getNewSonarWithTestInput('\fixtures\day-1-testinput-2.txt');
        $this->assertEquals(
            3,
            $sonar->countInclines()
        );
    }

    public function testCanMergeDatapointsByCount()
    {
        $sonar = self::getNewSonarWithTestInput('\fixtures\day-1-testinput.txt');
        $sonar->mergeDatapointsByCount(3);
        $reflectionClass = new ReflectionClass('App\Sonar');
        $data = $reflectionClass->getProperty('data')->getValue($sonar);
        $this->assertEquals(
            [
                607,
                618,
                618,
                617,
                647,
                716,
                769,
                792
            ],
            $data
        );
    }

    public function testCanReturnCorrectInclineCountAfterMerge()
    {
        $sonar = self::getNewSonarWithTestInput('\fixtures\day-1-testinput.txt');
        $sonar->mergeDatapointsByCount(3);
        $this->assertEquals(
            5,
            $sonar->countInclines()
        );
    }

    public function testCanReturnCorrectInclineCountAfterMerge2()
    {
        $sonar = self::getNewSonarWithTestInput('\fixtures\day-1-testinput-2.txt');
        $sonar->mergeDatapointsByCount(3); // 200, 160, 80, 100

        $this->assertEquals(
            1,
            $sonar->countInclines()
        );
    }

    /**
     * @throws TypeError
     */
    public function testThrowsTypeErrorIfCurrentitemNotInteger()
    {
        $this->expectException(TypeError::class);
        $sonar = self::getNewSonarWithTestInput('\fixtures\day-1-testinput.txt');
        $sonar->setCurrentitem('foo');
    }

    /**
     * @throws TypeError
     */
    public function testThrowsTypeErrorIfPreviousitemNotInteger()
    {
        $this->expectException(TypeError::class);
        $sonar = self::getNewSonarWithTestInput('\fixtures\day-1-testinput.txt');
        $sonar->setPreviousitem('bar');
    }

    public function testIsInclineReturnsTrueIfCurrentIsGreater()
    {
        $sonar = self::getNewSonarWithTestInput('\fixtures\day-1-testinput.txt');
        $sonar->setCurrentitem(100);
        $sonar->setPreviousitem(50);
        $this->assertTrue($sonar->isIncline());
    }

    public function testIsInclineReturnsFalseIfPreviousIsGreater()
    {
        $sonar = self::getNewSonarWithTestInput('\fixtures\day-1-testinput.txt');
        $sonar->setCurrentitem(50);
        $sonar->setPreviousitem(100);
        $this->assertFalse($sonar->isIncline());
    }

    public function testIsInclineReturnsFalseIfItemsEqual()
    {
        $sonar = self::getNewSonarWithTestInput('\fixtures\day-1-testinput.txt');
        $sonar->setCurrentitem(100);
        $sonar->setPreviousitem(100);
        $this->assertFalse($sonar->isIncline());
    }

    public function testIsInclineReturnsFalseIfPreviousNotSet()
    {
        $sonar = self::getNewSonarWithTestInput('\fixtures\day-1-testinput.txt');
        $sonar->setCurrentitem(100);
        $this->assertFalse($sonar->isIncline());
    }
}