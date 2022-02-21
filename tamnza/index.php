<?php

session_start();

require('tamnza/autoloader.php');

use Tamnza\Core\{Router, Route};

$router = new Router($_GET['url'] ?? '/');

$routes = array(
    \Tamnza\Core\includes("/", 'tamnza/urls.php'),
    new Route('/error', function () {
        require(BASE_DIR . 'views/500.php');
    }, name: "error"),
    new Route('<path:path>', function () {
        require(BASE_DIR . 'views/404.php');
    }),
);

$router->extends($routes);

$router->run();
