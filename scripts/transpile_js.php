#!/usr/bin/env php
<?php
require dirname(__DIR__) . '/vendor/autoload.php';
$file = dirname(__DIR__) . '/public/assets/script';
file_put_contents($file . '.compat.js', Babel\Transpiler::transformFile($file . '.js'));
ob_end_clean();
