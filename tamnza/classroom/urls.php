<?php

use Tamnza\Core\{Route};

require(dirname(__FILE__) . '/controllers/Classroom.php');

$classroom = new \Tamnza\App\Classroom\Controller\Classroom();

$routes = array(
    new Route("$path", array($classroom, 'home')),
    new Route("$path/login", array($classroom, 'login')),
);
