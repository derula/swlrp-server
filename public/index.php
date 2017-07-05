<?php
use Incertitude\SWLRP\Application;
use Incertitude\SWLRP\Exceptions\HttpError;
require dirname(__DIR__) . '/vendor/autoload.php';
$uriParts = explode('/', substr($_SERVER['REQUEST_URI'], 1));
$route = array_shift($uriParts);
$app = new Application(dirname(__DIR__), $uriParts + $_GET, $uriParts + $_POST);
try {
    switch($route) {
        case 'edit':
            if ('POST' === $_SERVER['REQUEST_METHOD']) {
                $app->getAction('Save')->execute();
            }
            echo $app->getView('Editor')->render();
            break;
        case 'view':
            echo $app->getView('Viewer')->render();
            break;
        default:
            echo $app->getView('Error')->setCode(404)->render();
    }
} catch(HttpError $ex) {
    echo $app->getView('Error')->setCode($ex->getStatusCode())->render();
}
