<?php

use Tamnza\Core\{Route};

require(dirname(__FILE__) . '/controllers/Classroom.php');

$classroom = new \Tamnza\App\Classroom\Controller\Classroom();

$routes = array(
    new Route("$path", array($classroom, 'home'), name: 'home'),
    new Route("$path/login", array($classroom, 'login'), name: 'login'),
    new Route("$path/signup", array($classroom, 'signup'), name: 'signup'),
);
