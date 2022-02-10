<?php

namespace Tamnza\App\Classroom\Model;

require_once('Student.php');
require_once('Answer.php');

// Relationship between student and answer
class StudentAnswer
{
    private int $id = 0;

    public function __construct(
        public Student|null $student = null,
        public Answer|null $answer = null,
    ) {
        // We config the dao
        $this->dao = new \Tamnza\Database\BaseDAO(
            'classroom_studentanswer',
            'id',
            array('student_id', 'answer_id'),
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
        $fields = array('student_id' => $this->student->getID(), 'answer_id' => $this->answer->getID());

        // If is already created, we update
        if ($this->id == 0) {
            $this->id = $this->dao->insert($fields);
            return  $this->id;
        } else {
            return $this->dao->update($this->id, $fields);
        }
    }

    public static function search(int $id = null, Student $student = null, Answer $answer = null, int $limit = -1): array
    {
        $interested_student = new StudentAnswer();

        $data = $interested_student->dao->select(
            array(
                'id' => $id,
                'student_id' => $student?->getID(),
                'answer_id' => $answer?->getID(),
            ),
            $limit
        );

        // We convert data in array of interested_students
        $result = array();

        foreach ($data as $record) {
            $interested_student = new StudentAnswer();

            $interested_student->setID($record['id']);
            $interested_student->student = Student::getByID($record['student_id']);
            $interested_student->answer = Answer::getByID($record['answer_id']);

            $result[] = $interested_student;
        }

        return $result;
    }

    public static function getByID(int $id): StudentAnswer|null
    {
        $result = StudentAnswer::search(id: $id, limit: 1);

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
