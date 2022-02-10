<?php

declare(strict_types=1);

namespace Tamnza\Test\Core;

use PHPUnit\Framework\TestCase;

require_once(dirname(__FILE__) . '/../../tamnza/database.php');
require_once(dirname(__FILE__) . '/../../tamnza/settings.php');
require_once(dirname(__FILE__) . '/../models/TakenQuiz.php');
require_once('StudentTest.php');
require_once('QuizTest.php');

final class TakenQuizTest extends TestCase
{
    public function testCreate(): \Tamnza\App\Classroom\Model\TakenQuiz
    {
        $student = (new StudentTest())->testCreate();
        $quiz = (new QuizTest())->testCreate();
        $taken_quiz = new \Tamnza\App\Classroom\Model\TakenQuiz(score: random_int(0, 10), student: $student, quiz: $quiz);

        $this->assertEquals($taken_quiz->save(), true);

        return $taken_quiz;
    }

    public function testUpdate(): void
    {
        $taken_quiz = $this->testCreate();

        $this->assertEquals($taken_quiz->save(), true);
    }

    public function testGetByID(): void
    {
        $taken_quiz = $this->testCreate();

        $taken_quiz_copy = \Tamnza\App\Classroom\Model\TakenQuiz::getByID($taken_quiz->getID());

        $this->assertEquals($taken_quiz->getID(), $taken_quiz_copy->getID());
        $this->assertEquals($taken_quiz->student->getID(), $taken_quiz_copy->student->getID());
        $this->assertEquals($taken_quiz->quiz->getID(), $taken_quiz_copy->quiz->getID());
    }

    public function testDelete(): void
    {
        $taken_quiz = $this->testCreate();

        $this->assertEquals($taken_quiz->delete(), true);
    }
}
