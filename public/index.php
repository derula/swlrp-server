<?php
use Incertitude\SWLRP\Application;
use Incertitude\SWLRP\Exceptions\HttpError;
use Incertitude\SWLRP\Exceptions\RedirectError;
use Incertitude\SWLRP\Exceptions\GetNewestFile;
use Incertitude\SWLRP\Exceptions\ShowHomepage;
require dirname(__DIR__) . '/vendor/autoload.php';
$app = new Application(dirname(__DIR__), $_SERVER, $_GET, $_POST);
try {
    switch($app->getRoute()) {
        case 'front':
            if ('POST' === $_SERVER['REQUEST_METHOD']) {
                $app->getAction('SaveAccount')->execute();
            }
            $view = $app->getView('Front');
            break;
        case 'edit':
            $view = $app->getView('Editor');
            $app->getSession()->assertLoggedIn($view);
            if ('POST' === $_SERVER['REQUEST_METHOD']) {
                $app->getAction('SaveProfile')->execute();
            }
            break;
        case 'view':
            $view = $app->getView('Viewer');
            break;
        case 'suggestions':
            $view = $app->getView('Suggestions');
            break;
        case 'changepw':
            $app->getSession()->assertLoggedIn();
            $app->getAction('UpdatePassword')->execute();
            exit;
        case 'download':
            $app->getAction('Download')->execute();
            exit;
        case '':
            $app->getAction('ShowHomepage')->execute();
            exit;
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
