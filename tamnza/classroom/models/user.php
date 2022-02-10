<?php

namespace Tamnza\App\Classroom\Model;

require_once('student.php');
require_once('quiz.php');

class User
{
    public \Datetime $date_joined;
    public \Datetime $last_login;
    private \Tamnza\Database\BaseDAO $dao;
    private int $id = 0;

    public function __construct(
        public string $username = '',
        public string $first_name = '',
        public string $last_name = '',
        public string $email = '',
        public string $password = '',
        public bool $is_student = false,
        public bool $is_teacher = false,
        public bool $is_active = true,
    ) {
        $this->date_joined = new \Datetime();
        $this->last_login = new \Datetime();
        // We config the dao
        $this->dao = new \Tamnza\Database\BaseDAO(
            'classroom_user',
            'id',
            array(
                'username',
                'first_name',
                'last_name',
                'email',
                'password',
                'is_student',
                'is_teacher',
                'is_active',
                'date_joined',
                'last_login',
            )
        );
    }

    public function getID(): int
    {
        return $this->id;
    }

    public function setID(int $id)
    {
        if ($this->id == 0) {
            $this->id = $id;
        } else {
            throw new \Exception("You cannot assign an id, this object is sync with the database");
        }
    }

    // We will manipulate special attributes
    public function __get(string $key): array|Student|null
    {
        return match ($key) {
            'quizzes' => Quiz::search(owner: $this),
            'student' => Student::getByID(id: $this->getID()),
            default => throw new \Exception("$key is not a special attribute"),
        };
    }

    public function save(): bool
    {
        $fields = array(
            'username' => $this->username,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'password' => $this->password,
            'is_student' => $this->is_student ? 1 : 0,
            'is_teacher' => $this->is_teacher ? 1 : 0,
            'is_active' => $this->is_active ? 1 : 0,
            'date_joined' => $this->date_joined->format('Y-m-d H:i:s'),
            'last_login' => $this->last_login->format('Y-m-d H:i:s')
        );

        // If is already created, we update
        if ($this->id == 0) {
            $this->id = $this->dao->insert($fields);
            return $this->id != 0;
        } else {
            return $this->dao->update($this->id, $fields);
        }
    }

    public static function search(int $id = null, string $username = null, string $first_name = null, string $last_name = null, string $email = null, string $password = null, bool $is_student = null, bool $is_teacher = null, bool $is_active = null, \Datetime $date_joined = null, \Datetime $last_login = null, int $limit = -1): array
    {
        $user = new User();

        $data = $user->dao->select(
            array(
                'id' => $id,
                'username' => $username,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'password' => $password,
                'is_student' => is_null($is_student) ? null : ($is_student ? 1 : 0),
                'is_teacher' => is_null($is_teacher) ? null : ($is_teacher ? 1 : 0),
                'is_active' => is_null($is_active) ? null : ($is_active ? 1 : 0),
            ),
            $limit
        );

        // We convert data in array of users
        $result = array();

        foreach ($data as $record) {
            $user = new User(username: $record['username'], first_name: $record['first_name'], last_name: $record['last_name'], email: $record['email'], password: $record['password'], is_student: $record['is_student'], is_teacher: $record['is_teacher']);

            $user->setID($record['id']);
            $user->date_joined = \Datetime::createFromFormat('Y-m-d H:i:s', $record['date_joined']);
            $user->last_login = \Datetime::createFromFormat('Y-m-d H:i:s', $record['last_login']);

            $result[] = $user;
        }

        return $result;
    }

    public static function getByID(int $id): User|null
    {
        $result = User::search(id: $id, limit: 1);

        if (count($result) > 0) {
            return $result[0];
        } else {
            return null;
        }
    }

    public function delete(): bool
    {
        return $this->dao->delete($this->id);
    }
}
