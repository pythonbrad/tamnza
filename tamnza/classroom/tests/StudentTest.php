<?php

declare(strict_types=1);

namespace Tamnza\Test\Core;

use PHPUnit\Framework\TestCase;

require_once(dirname(__FILE__) . '/../../tamnza/database.php');
require_once(dirname(__FILE__) . '/../../tamnza/settings.php');
require_once(dirname(__FILE__) . '/../models/student.php');
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
}
