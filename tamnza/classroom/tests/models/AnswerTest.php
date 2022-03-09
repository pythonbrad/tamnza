<?php

namespace Tamnza\Test\Model;

use PHPUnit\Framework\TestCase;

require_once(dirname(__FILE__) . '/../../../tamnza/database.php');
require_once(dirname(__FILE__) . '/../../../tamnza/settings.php');
require_once(dirname(__FILE__) . '/../../models/Answer.php');
require_once('QuestionTest.php');

final class AnswerTest extends TestCase
{
    public function testCreate(): \Tamnza\App\Classroom\Model\Answer
    {
        $question = (new QuestionTest())->testCreate();
        $answer = new \Tamnza\App\Classroom\Model\Answer(text: sprintf('%s', rand()), question: $question, is_correct: true);

        $this->assertEquals($answer->save(), true);

        return $answer;
    }

    public function testUpdate(): void
    {
        $answer = $this->testCreate();

        $answer->text = sprintf('%s', rand());

        $this->assertEquals($answer->save(), true);
    }

    public function testGetByID(): void
    {
        $answer = $this->testCreate();

        $answer_copy = \Tamnza\App\Classroom\Model\Answer::getByID($answer->getID());

        $this->assertEquals($answer->getID(), $answer_copy->getID());
        $this->assertEquals($answer->text, $answer_copy->text);
        $this->assertEquals($answer->is_correct, $answer_copy->is_correct);
        $this->assertEquals($answer->question->getID(), $answer_copy->question->getID());
    }

    public function testDelete(): void
    {
        $answer = $this->testCreate();

        $this->assertEquals($answer->delete(), true);
    }
}
