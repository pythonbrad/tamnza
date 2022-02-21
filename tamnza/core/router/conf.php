<?php

namespace Tamnza\Core;

require_once('router.php');

/**
 * This function permit to join another url file configuration
 **/
function includes(string $path, string $extension): array
{
    $path = rtrim($path, '/');

    require_once($extension);

    if (isset($routes)) {
        return $routes;
    } else {
        throw new \Exception("$extension don't contains the routes");
    }
}
