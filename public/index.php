<?php
use Incertitude\SWLRP;
require '../vendor/autoload.php';
$config = new Config(__DIR__ . '../config/config.yml');
$pdo = new \PDO(
    $config->get('DB', 'dsn'),
    $config->get('DB', 'user'),
    $config->get('DB', 'password')
);
$model = new Models\Profile($pdo, $config);
switch($_SERVER['REQUEST_URI']) {
    case '/edit':
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            (new Actions\Save($_POST, $model))->execute();
        }
        echo (new Views\Editor($_GET, $model))->render();
    case '/view':
        echo (new Views\Viewer($_GET, $model))->render();
}
