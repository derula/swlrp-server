<?php
use Incertitude\SWLRP;
require '../vendor/autoload.php';
$app = new Application(dirname(__DIR__));
echo 'Deleting old properties and fixing type of existing properties...';
$app->getModel('Profile')->refreshProperties();
echo 'Done.';
