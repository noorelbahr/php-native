<?php

require_once './config/app.php';
require_once './routes/api.php';
require_once './helpers/HttpClient.php';
require_once 'App.php';

/**
 * Autoload classes
 * - - -
 * @param $className
 */
function __autoload($className)
{
    if (file_exists('./core/' . $className . '.php')) {
        require_once './core/' . $className . '.php';
    } elseif (file_exists('./libraries/' . $className . '.php')) {
        require_once './libraries/' . $className . '.php';
    } elseif (file_exists('./app/Controllers/' . $className . '.php')) {
        require_once './app/Controllers/' . $className . '.php';
    } elseif (file_exists('./app/Models/' . $className . '.php')) {
        require_once './app/Models/' . $className . '.php';
    }
}

$app = new App();

$app->run();
