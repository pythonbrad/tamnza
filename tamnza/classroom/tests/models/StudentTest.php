<?php

namespace Tamnza\Test\Model;

use PHPUnit\Framework\TestCase;

require_once(dirname(__FILE__) . '/../../../tamnza/database.php');
require_once(dirname(__FILE__) . '/../../../tamnza/settings.php');
require_once(dirname(__FILE__) . '/../../models/Student.php');
require_once('UserTest.php');

final class StudentTest extends TestCase
{
    public function testCreate(): \Tamnza\App\Classroom\Model\Student
    {
        $user = (new UserTest())->testCreate();
        $student = new \Tamnza\App\Classroom\Model\Student(user: $user);

        $this->assertEquals($student->save(), true);

        return $student;
    }

    public function testUpdate(): void
    {
        $student = $this->testCreate();

        $this->assertEquals($student->save(), true);
    }

    public function testGetByID(): void
    {
        $student = $this->testCreate();

        $student_copy = \Tamnza\App\Classroom\Model\Student::getByID($student->getID());

        $this->assertEquals($student->getID(), $student_copy->getID());
    }

    public function testDelete(): void
    {
        $student = $this->testCreate();

        $this->assertEquals($student->delete(), true);
    }

    public function testGetInterests(): void
    {
        $student = $this->testCreate();

        $this->assertIsArray($student->interests);
    }

    public function testGetQuizAnswers(): void
    {
        $student = $this->testCreate();

        $this->assertIsArray($student->quiz_answers);
    }

    public function testGetTakenQuizzes(): void
    {
        $student = $this->testCreate();

        $this->assertIsArray($student->taken_quizzes);
    }
}
