<?php
require_once __DIR__ . '\vendor\autoload.php';

use App\Input\TxtInput;
use App\InputReader\TxtReader;
use App\Sonar;

$txtreader = new TxtReader();
$sonardata = new TxtInput(__DIR__ . '\tests\fixtures\day-1-sonar-input.txt', 'txt');
$txtreader->setInput($sonardata);
$sonar = new Sonar($txtreader);

$sonar->mergeDatapointsByCount(3);
$count = $sonar->countInclines();
echo $count;