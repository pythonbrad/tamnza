<?php

use Tamnza\Core\{Route};

require(dirname(__FILE__) . '/controllers/Classroom.php');
require(dirname(__FILE__) . '/controllers/Student.php');
require(dirname(__FILE__) . '/controllers/Teacher.php');

$classroom = new \Tamnza\App\Classroom\Controller\Classroom();
$student = new \Tamnza\App\Classroom\Controller\Student();
$teacher = new \Tamnza\App\Classroom\Controller\Teacher();

$routes = array(
    # home
    new Route("$path", array($classroom, 'home'), name: 'home'),

    # authentification
    new Route("$path/login", array($classroom, 'login'), name: 'login'),
    new Route("$path/signup", array($classroom, 'signup'), name: 'signup'),
    new Route("$path/logout", array($classroom, 'logout'), name: 'logout'),

    # main menu
    new Route("$path/student_signup", array($student, 'signup'), name: 'student_signup'),
    new Route("$path/teacher_signup", array($teacher, 'signup'), name: 'teacher_signup'),

    # teacher menu
    new Route("$path/teacher", array($teacher, 'quizChangeList'), name: 'quiz_change_list'),
    new Route("$path/teacher/quiz/add", array($teacher, 'quizAdd'), name: 'quiz_add'),
    new Route("$path/teacher/quiz/<pk:int>/change", array($teacher, 'quizChange'), name: 'quiz_change'),
    new Route("$path/teacher/quiz/<pk:int>/results", array($teacher, 'quizResults'), name: 'quiz_results'),
    new Route("$path/teacher/quiz/<pk:int>/delete", array($teacher, 'quizDelete'), name: 'quiz_delete'),
    new Route("$path/teacher/quiz/<quiz_pk:int>/question/add", array($teacher, 'questionAdd'), name: 'question_add'),
    new Route("$path/teacher/quiz/<quiz_pk:int>/question/<question_pk:int>", array($teacher, 'questionChange'), name: 'question_change'),
    new Route("$path/teacher/quiz/<quiz_pk:int>/question/<question_pk:int>/delete", array($teacher, 'questionDelete'), name: 'question_delete'),

    # student menu
    new Route("$path/student", array($student, 'quizList'), name: 'quiz_list'),
    new Route("$path/student/interests", array($student, 'studentInterests'), name: 'student_interests'),
    new Route("$path/student/taken", array($student, 'takenQuizList'), name: 'taken_quiz_list'),
    new Route("$path/student/quiz/<pk:int>", array($student, 'takeQuiz'), name: 'take_quiz'),
);
