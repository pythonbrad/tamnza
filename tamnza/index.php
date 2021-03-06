<?php

namespace Tamnza;

session_start();

require('tamnza/autoloader.php');

$router = new Core\Router($_GET['url'] ?? '/');

$routes = array(
    Core\includes("/", 'tamnza/urls.php'),
    new Core\Route('/error', function () {
        header("HTTP/1.1 500 Internal Server Error");
        require(BASE_DIR . 'views/500.php');
    }, name: "error"),
    new Core\Route('<path:path>', function () {
        header("HTTP/1.1 404 Not Found");
        require(BASE_DIR . 'views/404.php');
    }),
);

$router->extends($routes);

$router->run();
