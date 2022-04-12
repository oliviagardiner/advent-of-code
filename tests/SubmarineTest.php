<?php

use App\Input\TxtInput;
use App\InputReader\TxtReader;
use App\Submarine;
use App\Exceptions\NotFileException;
use App\Exceptions\IncorrectExtensionException;
use App\Exceptions\InvalidCommandException;
use App\Exceptions\InvalidNavigationValueException;
use PHPUnit\Framework\TestCase;

//./vendor/bin/phpunit tests
class SubmarineTest extends TestCase
{
    public static Submarine $submarine;
    public static ReflectionClass $reflectionClass;

    /**
     * @throws NotFileException
     * @throws IncorrectExtensionException
     */
    public static function getNewSubmarineWithTestInput(string $path): Submarine
    {
        $testcourse = new TxtInput(__DIR__ . $path, 'txt');
        $testreader = new TxtReader();
        $testreader->setInput($testcourse);
        return new Submarine($testreader);
    }

    public function testIncreaseDepthChangesPropertyCorrectly()
    {
        self::$submarine = self::getNewSubmarineWithTestInput('\fixtures\day-2-testinput.txt');
        self::$submarine->increaseDepth(25);
        self::$reflectionClass = new ReflectionClass('App\Submarine');
        $depth = self::$reflectionClass->getProperty('depthPosition')->getValue(self::$submarine);
        $this->assertEquals(
            25,
            $depth
        );
    }

    public function testDecreaseDepthChangesPropertyCorrectly()
    {
        self::$submarine->decreaseDepth(10);
        $depth = self::$reflectionClass->getProperty('depthPosition')->getValue(self::$submarine);
        $this->assertEquals(
            15,
            $depth
        );
    }

    public function testMoveHorizontallyChangesPropertyCorrectly()
    {
        self::$submarine->moveHorizontally(50);
        $horiz = self::$reflectionClass->getProperty('horizontalPosition')->getValue(self::$submarine);
        $this->assertEquals(
            50,
            $horiz
        );
    }

    public function testExtractCommandSplitsLineCorrectly()
    {
        $this->assertEquals(
            ['forward', 5],
            self::$submarine->extractCommand('forward 5\n')
        );
    }

    public function testIncorrectCommandValidationReturnsTrue()
    {
        $this->assertEquals(
            true,
            self::$submarine->notValidCommand('back')
        );
    }

    public function testCorrectCommandValidationReturnsFalse()
    {
        $this->assertEquals(
            false,
            self::$submarine->notValidCommand('forward')
        );
    }

    public function testNegativeValueValidationReturnsTrue()
    {
        $this->assertEquals(
            true,
            self::$submarine->notValidNavigationValue(-5)
        );
    }

    public function testCorrectValueValidationReturnsFalse()
    {
        $this->assertEquals(
            false,
            self::$submarine->notValidNavigationValue(5)
        );
    }

    public function testNavigateChangesPropertyCorrectly()
    {
        $submarine = self::getNewSubmarineWithTestInput('\fixtures\day-2-testinput.txt');
        $submarine->navigate();
        $reflectionClass = new ReflectionClass('App\Submarine');
        $depth = $reflectionClass->getProperty('depthPosition')->getValue($submarine);
        $this->assertEquals(
            10,
            $depth
        );
    }

    public function testCalculatePositionAfterNavigate()
    {
        $submarine = self::getNewSubmarineWithTestInput('\fixtures\day-2-testinput.txt');
        $submarine->navigate();
        $this->assertEquals(
            150,
            $submarine->calculatePosition()
        );
    }
}