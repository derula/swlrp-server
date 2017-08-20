#!/usr/bin/env php
<?php
use Incertitude\SWLRP\Application;

$name = array_shift($argv);
if (4 !== $argc) {
    die(<<<HELP
Usage: $name <firts> <nick> <last>
Example: $name Dwayne "TheRock" Johnson

HELP
    );
}

require dirname(__DIR__) . '/vendor/autoload.php';
$app = new Application(dirname(__DIR__));
try {
    echo $app->getModel('Account')->resetPassword(...$argv), PHP_EOL;
} catch (\Exception $e) {
    echo $e->getMessage(), PHP_EOL;
}
