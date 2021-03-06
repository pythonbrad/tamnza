<?php

namespace Tamnza\Test\Model;

use PHPUnit\Framework\TestCase;

require_once(dirname(__FILE__) . '/../../../tamnza/database.php');
require_once(dirname(__FILE__) . '/../../../tamnza/settings.php');
require_once(dirname(__FILE__) . '/../../models/Subject.php');

final class SubjectTest extends TestCase
{
    public function testCreate(): \Tamnza\App\Classroom\Model\Subject
    {
        $subject = new \Tamnza\App\Classroom\Model\Subject(name: sprintf('%s', rand()), color: 'red');

        $this->assertEquals($subject->save(), true);

        return $subject;
    }

    public function testUpdate(): void
    {
        $subject = $this->testCreate();

        $temp = $subject->name;
        $subject->name = sprintf('%s', rand());

        $this->assertEquals($subject->save(), true);
    }

    public function testGetByID(): void
    {
        $subject = $this->testCreate();

        $subject_copy = \Tamnza\App\Classroom\Model\Subject::getByID($subject->getID());

        $this->assertEquals($subject->name, $subject_copy->name);
        $this->assertEquals($subject->color, $subject_copy->color);
    }

    public function testDelete(): void
    {
        $subject = $this->testCreate();

        $this->assertEquals($subject->delete(), true);
    }

    public function testGetQuizzes(): void
    {
        $subject = $this->testCreate();

        $this->assertIsArray($subject->quizzes);
        $this->assertIsArray($subject->interested_students);
    }
}
