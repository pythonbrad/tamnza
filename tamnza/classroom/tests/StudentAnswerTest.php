<?php

declare(strict_types=1);

namespace Tamnza\Test\Core;

use PHPUnit\Framework\TestCase;

require_once(dirname(__FILE__) . '/../../tamnza/database.php');
require_once(dirname(__FILE__) . '/../../tamnza/settings.php');
require_once(dirname(__FILE__) . '/../models/StudentAnswer.php');
require_once('StudentTest.php');
require_once('AnswerTest.php');

final class StudentAnswerTest extends TestCase
{
    public function testCreate(): \Tamnza\App\Classroom\Model\StudentAnswer
    {
        $student = (new StudentTest())->testCreate();
        $answer = (new AnswerTest())->testCreate();
        $interested_student = new \Tamnza\App\Classroom\Model\StudentAnswer(student: $student, answer: $answer);

        $this->assertEquals($interested_student->save(), true);

        return $interested_student;
    }

    public function testUpdate(): void
    {
        $interested_student = $this->testCreate();

        $this->assertEquals($interested_student->save(), true);
    }

    public function testGetByID(): void
    {
        $interested_student = $this->testCreate();

        $interested_student_copy = \Tamnza\App\Classroom\Model\StudentAnswer::getByID($interested_student->getID());

        $this->assertEquals($interested_student->getID(), $interested_student_copy->getID());
        $this->assertEquals($interested_student->student->getID(), $interested_student_copy->student->getID());
        $this->assertEquals($interested_student->answer->getID(), $interested_student_copy->answer->getID());
    }

    public function testDelete(): void
    {
        $interested_student = $this->testCreate();

        $this->assertEquals($interested_student->delete(), true);
    }
}
