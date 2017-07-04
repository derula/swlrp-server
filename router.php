<?php
switch($_SERVER['REQUEST_URI']) {
    case '/edit':
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            require 'actions/save.php';
        }
        require 'views/editor.php';
    case '/view':
        require 'views/viewer.php';
}
