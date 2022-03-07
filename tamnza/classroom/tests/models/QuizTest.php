<?php

namespace Tamnza\Test\Model;

use PHPUnit\Framework\TestCase;

require_once(dirname(__FILE__) . '/../../../tamnza/database.php');
require_once(dirname(__FILE__) . '/../../../tamnza/settings.php');
require_once(dirname(__FILE__) . '/../../models/Quiz.php');
require_once('UserTest.php');
require_once('SubjectTest.php');

final class QuizTest extends TestCase
{
    public function testCreate(): \Tamnza\App\Classroom\Model\Quiz
    {
        $owner = (new UserTest())->testCreate();
        $subject = (new SubjectTest())->testCreate();
        $quiz = new \Tamnza\App\Classroom\Model\Quiz(owner: $owner, name: sprintf('%s', rand()), subject: $subject);

        $this->assertEquals($quiz->save(), true);

        return $quiz;
    }

    public function testUpdate(): void
    {
        $quiz = $this->testCreate();

        $quiz->name = sprintf('%s', rand());

        $this->assertEquals($quiz->save(), true);
    }

    public function testGetByID(): void
    {
        $quiz = $this->testCreate();

        $quiz_copy = \Tamnza\App\Classroom\Model\Quiz::getByID($quiz->getID());

        $this->assertEquals($quiz->getID(), $quiz_copy->getID());
        $this->assertEquals($quiz->name, $quiz_copy->name);
        $this->assertEquals($quiz->owner->getID(), $quiz_copy->owner->getID());
        $this->assertEquals($quiz->subject->getID(), $quiz_copy->subject->getID());
    }

    public function testDelete(): void
    {
        $quiz = $this->testCreate();

        $this->assertEquals($quiz->delete(), true);
    }

    public function testGetQuestions(): void
    {
        $quiz = $this->testCreate();

        $this->assertIsArray($quiz->questions);
    }

    public function testGetTakenQuizzes(): void
    {
        $quiz = $this->testCreate();

        $this->assertIsArray($quiz->taken_quizzes);
    }

    public function testAverageScore(): void
    {
        $quiz = $this->testCreate();

        $this->assertEquals($quiz->averageScore(), 0.0);
    }
}
