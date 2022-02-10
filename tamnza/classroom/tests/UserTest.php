<?php

declare(strict_types=1);

namespace Tamnza\Test\Core;

use PHPUnit\Framework\TestCase;

require_once(dirname(__FILE__) . '/../../tamnza/database.php');
require_once(dirname(__FILE__) . '/../../tamnza/settings.php');
require_once(dirname(__FILE__) . '/../models/user.php');

final class UserTest extends TestCase
{
    public function testCreate(): \Tamnza\App\Classroom\Model\User
    {
        $user = new \Tamnza\App\Classroom\Model\User(username: sprintf('%s', rand()), first_name: sprintf('%s', rand()), last_name: sprintf('%s', rand()), email: sprintf(rand() . '@example.com'), password: sprintf('%s', rand()), is_student: false, is_teacher: true);

        $this->assertEquals($user->save(), true);

        return $user;
    }

    public function testUpdate(): void
    {
        $user = $this->testCreate();

        $temp = $user->username;
        $user->username = sprintf('%s', rand());

        $this->assertEquals($user->save(), true);
    }

    public function testGetByID(): void
    {
        $user = $this->testCreate();

        $user_copy = \Tamnza\App\Classroom\Model\User::getByID($user->getID());

        $this->assertEquals($user->date_joined->getTimestamp(), $user->last_login->getTimestamp());
        $this->assertEquals($user->username, $user_copy->username);
        $this->assertEquals($user->first_name, $user_copy->first_name);
        $this->assertEquals($user->last_name, $user_copy->last_name);
        $this->assertEquals($user->email, $user_copy->email);
        $this->assertEquals($user->password, $user_copy->password);
        $this->assertEquals($user->is_student, $user_copy->is_student);
        $this->assertEquals($user->is_teacher, $user_copy->is_teacher);
    }

    public function testDelete(): void
    {
        $user = $this->testCreate();

        $this->assertEquals($user->delete(), true);
    }

    public function testGetQuizzes(): void
    {
        $user = $this->testCreate();

        $this->assertIsArray($user->quizzes);
    }

    public function getStudent(): void
    {
        $user = $this->testCreate();

        $this->assertEquals($user->getID(), $user->student->user->getID());
    }
}
