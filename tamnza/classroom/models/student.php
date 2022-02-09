<?php

namespace Tamnza\App\Classroom\Model;

class Student
{
    private int $id = 0;

    public function __construct(public User|null $user = null)
    {
        // We config the dao
        $this->dao = new \Tamnza\Database\BaseDAO(
            'classroom_student',
            'user_id',
            array(),
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

    public function save(): bool
    {
        $fields = array('user_id' => $this->user->getID());

        // If is already created, we update
        if ($this->id == 0) {
            $this->dao->insert($fields);
            $this->id = $fields['user_id'];
            return $this->id;
        } else {
            return $this->dao->update($this->id, $fields);
        }
    }

    public static function search(int $id = null, int $limit = -1): array
    {
        $student = new Student();

        $data = $student->dao->select(array('user_id' => $id), $limit);

        // We convert data in array of students
        $result = array();

        foreach ($data as $record) {
            $student = new Student();

            $student->setID($record['user_id']);
            $student->user = User::getByID($record['user_id']);

            $result[] = $student;
        }

        return $result;
    }

    public static function getByID(int $id): Student|null
    {
        $result = Student::search(id: $id, limit: 1);

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
