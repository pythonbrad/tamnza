<?php

use Tamnza\Core\{Route};

require(dirname(__FILE__) . '/controllers/Classroom.php');
require(dirname(__FILE__) . '/controllers/Student.php');
require(dirname(__FILE__) . '/controllers/Teacher.php');

$classroom = new \Tamnza\App\Classroom\Controller\Classroom();
$student = new \Tamnza\App\Classroom\Controller\Student();
$teacher = new \Tamnza\App\Classroom\Controller\Teacher();

$routes = array(
    new Route("$path", array($classroom, 'home'), name: 'home'),
    new Route("$path/login", array($classroom, 'login'), name: 'login'),
    new Route("$path/signup", array($classroom, 'signup'), name: 'signup'),
    new Route("$path/logout", array($classroom, 'logout'), name: 'logout'),
    new Route("$path/student_signup", array($student, 'signup'), name: 'student_signup'),
    new Route("$path/teacher_signup", array($teacher, 'signup'), name: 'teacher_signup'),
);
