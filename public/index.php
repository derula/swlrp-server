<?php
use Incertitude\SWLRP\Application;
use Incertitude\SWLRP\Exceptions\HttpError;
require dirname(__DIR__) . '/vendor/autoload.php';
$app = new Application(dirname(__DIR__), $_SERVER, $_GET, $_POST);
try {
    switch($app->getRoute()) {
        case 'edit':
            if ('POST' === $_SERVER['REQUEST_METHOD']) {
                $app->getAction('Save')->execute();
            }
            echo $app->getView('Editor')->render();
            break;
        case 'view':
            echo $app->getView('Viewer')->render();
            break;
        case 'suggestions':
            echo $app->getView('Suggestions')->render();
            break;
        default:
            echo $app->getView('Error')->setCode(404)->render();
    }
} catch(HttpError $ex) {
    echo $app->getView('Error')->setCode($ex->getStatusCode())->render();
}
