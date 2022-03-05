<?php

namespace Tamnza\Test\Core;

use PHPUnit\Framework\TestCase;

require_once(dirname(__FILE__) . '/../../tamnza/database.php');
require_once(dirname(__FILE__) . '/../../tamnza/settings.php');
require_once(dirname(__FILE__) . '/../models/InterestedStudent.php');
require_once('StudentTest.php');
require_once('SubjectTest.php');

final class InterestedStudentTest extends TestCase
{
    public function testCreate(): \Tamnza\App\Classroom\Model\InterestedStudent
    {
        $student = (new StudentTest())->testCreate();
        $subject = (new SubjectTest())->testCreate();
        $interested_student = new \Tamnza\App\Classroom\Model\InterestedStudent(student: $student, subject: $subject);

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

        $interested_student_copy = \Tamnza\App\Classroom\Model\InterestedStudent::getByID($interested_student->getID());

        $this->assertEquals($interested_student->getID(), $interested_student_copy->getID());
        $this->assertEquals($interested_student->student->getID(), $interested_student_copy->student->getID());
        $this->assertEquals($interested_student->subject->getID(), $interested_student_copy->subject->getID());
    }

    public function testDelete(): void
    {
        $interested_student = $this->testCreate();

        $this->assertEquals($interested_student->delete(), true);
    }
}
