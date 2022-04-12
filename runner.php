<?php
require_once __DIR__ . '\vendor\autoload.php';

use App\Input\TxtInput;
use App\InputReader\TxtReader;
use App\Sonar;

$input = new TxtInput(__DIR__ . '\tests\fixtures\day-1-sonar-input.txt', 'txt');
$reader = new TxtReader($input);
$sonar = new Sonar($reader);

$sonar->mergeDatapointsByCount(3);
$count = $sonar->countInclines();
echo $count;