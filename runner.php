<?php
require_once __DIR__ . '\vendor\autoload.php';

use App\Reader\SonarDataReader;
use App\Reader\NavigationReader;
use App\Reader\DiagnosticsReader;
use App\Reader\BingoReader;
use App\Reader\BingoNumberReader;
use App\Sonar;
use App\Submarine;
use App\Diagnostics;
use App\Game\Bingo\Bingo;

$sonarDataReader = new SonarDataReader(__DIR__ . '\tests\fixtures\day-1-sonar-input.txt');
$sonar = new Sonar($sonarDataReader);

$sonar->mergeDatapointsByCount(3);
$count = $sonar->countInclines();
echo $count . PHP_EOL;

$navigationReader = new NavigationReader(__DIR__ . '\tests\fixtures\day-2-navigation-input.txt');
$submarine = new Submarine($navigationReader);
$submarine->navigate();
$position = $submarine->calculatePosition();
echo $position . PHP_EOL;

$diagnosticsReader = new DiagnosticsReader(__DIR__ . '\tests\fixtures\day-3-diagnostics-input.txt');
$diagnostics = new Diagnostics($diagnosticsReader);
$powercon = $diagnostics->getPowerConsumption();
echo $powercon . PHP_EOL;

$lifesupport = $diagnostics->getLifeSupportRating();
echo $lifesupport . PHP_EOL;

$bingoreader = new BingoReader(__DIR__ . '\tests\fixtures\day-4-bingoinput.txt');
$numberreader = new BingoNumberReader(__DIR__ . '\tests\fixtures\day-4-bingoinput.txt');
$bingo = new Bingo($bingoreader, $numberreader);
$bingo->play();
$bingo->announceWinner();