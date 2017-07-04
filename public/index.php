<?php
use Incertitude\SWLRP\Application;
require '../vendor/autoload.php';
$app = new Application(dirname(__DIR__), $_GET, $_POST);
switch($_SERVER['REQUEST_URI']) {
    case '/edit':
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $app->getAction('Save')->execute();
        }
        echo $app->getView('Editor')->render();
    case '/view':
        echo $app->getView('Viewer')->render();
    default:
        echo $app->getView('Error')->setCode(404)->render();
}
