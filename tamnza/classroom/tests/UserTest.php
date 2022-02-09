<?php

declare(strict_types=1);

namespace Tamnza\Test\Core;

use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    public function testCreate(): \Tamnza\App\Classroom\Model\User
    {
        require_once(dirname(__FILE__) . '/../../tamnza/database.php');
        require_once(dirname(__FILE__) . '/../../tamnza/settings.php');
        require_once(dirname(__FILE__) . '/../models/user.php');

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

        // we ignore the small diffrence of time
        if ($user->date_joined->getTimestamp() - $user->last_login->getTimestamp() < 1) {
            $user_copy->last_login = $user->last_login;
            $user_copy->date_joined = $user->date_joined;
        }

        $this->assertEquals($user, $user_copy);
    }

    public function testDelete(): void
    {
        $user = $this->testCreate();

        $this->assertEquals($user->delete(), true);
    }
}
