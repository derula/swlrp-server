#!/usr/bin/env php
<?php
use Incertitude\SWLRP\Application;
require dirname(__DIR__) . '/vendor/autoload.php';
$app = new Application(dirname(__DIR__));
echo 'Deleting old properties and fixing type of existing properties...', PHP_EOL;
$app->getModel('Profile')->refreshProperties();
echo 'Done.';
