<?php

declare(strict_types=1);

namespace Tamnza\Test\Core;

use PHPUnit\Framework\TestCase;

require_once(dirname(__FILE__) . '/../../tamnza/database.php');
require_once(dirname(__FILE__) . '/../../tamnza/settings.php');
require_once(dirname(__FILE__) . '/../models/Question.php');
require_once('QuizTest.php');

final class QuestionTest extends TestCase
{
    public function testCreate(): \Tamnza\App\Classroom\Model\Question
    {
        $quiz = (new QuizTest())->testCreate();
        $question = new \Tamnza\App\Classroom\Model\Question(text: sprintf('%s', rand()), quiz: $quiz);

        $this->assertEquals($question->save(), true);

        return $question;
    }

    public function testUpdate(): void
    {
        $question = $this->testCreate();

        $question->text = sprintf('%s', rand());

        $this->assertEquals($question->save(), true);
    }

    public function testGetByID(): void
    {
        $question = $this->testCreate();

        $question_copy = \Tamnza\App\Classroom\Model\Question::getByID($question->getID());

        $this->assertEquals($question->getID(), $question_copy->getID());
        $this->assertEquals($question->text, $question_copy->text);
        $this->assertEquals($question->quiz->getID(), $question_copy->quiz->getID());
    }

    public function testDelete(): void
    {
        $question = $this->testCreate();

        $this->assertEquals($question->delete(), true);
    }
}
