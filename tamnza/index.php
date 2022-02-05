<?php

require_once('tamnza/autoloader.php');

use Tamnza\Core\{Router};

$router = new Router($_GET['url'] ?? '/');

$routes = array(
    \Tamnza\Core\includes("", 'tamnza/urls.php'),
);

$router->extends($routes);

$router->run();
