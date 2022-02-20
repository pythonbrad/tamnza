<?php

use Tamnza\Core\{Route};

require(dirname(__FILE__) . '/controllers/Classroom.php');

$classroom = new Tamnza\App\Classroom\Controller\Classroom();

$routes = array(
    new Route("$path", $classroom->home),
);
