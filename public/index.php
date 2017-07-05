<?php
use Incertitude\SWLRP\Application;
use Incertitude\SWLRP\Exceptions\HttpError;
use Incertitude\SWLRP\Exceptions\RedirectError;
require dirname(__DIR__) . '/vendor/autoload.php';
$app = new Application(dirname(__DIR__), $_SERVER, $_GET, $_POST);
try {
    switch($app->getRoute()) {
        case 'front':
            $view = $app->getView('Front');
            break;
        case 'edit':
            $app->getSession()->assertLoggedIn();
            if ('POST' === $_SERVER['REQUEST_METHOD']) {
                $app->getAction('SaveProfile')->execute();
            }
            $view = $app->getView('Editor');
            break;
        case 'view':
            $view = $app->getView('Viewer');
            break;
        case 'suggestions':
            $view = $app->getView('Suggestions');
            break;
        default:
            $view = $app->getView('Error')->setCode(404);
    }
    echo $view->render();
} catch(RedirectError $ex) {
    http_response_code($ex->getStatusCode());
    header('Location: ' . $ex->getLocation());
} catch(HttpError $ex) {
    echo $app->getView('Error')->setCode($ex->getStatusCode())->render();
}
