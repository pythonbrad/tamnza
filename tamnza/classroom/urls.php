<?php

use Tamnza\Core\{Route};

$routes = array(
    new Route("$path/home", function () {
        echo 'Welcome to Tamnza Classroom';
    }),
);
