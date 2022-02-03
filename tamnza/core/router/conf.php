<?php

namespace Tamnza\Core;

require_once('router/router.php');

function includes(string $path, string $extension): array
{
    require_once($extension);

    if (isset($routes)) {
        return $routes;
    } else {
        throw new Exception("$extension don't contains the routes");
    }
}
