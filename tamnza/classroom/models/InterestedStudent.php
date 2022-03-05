<?php

namespace Tamnza\App\Classroom\Model;

require_once('Student.php');
require_once('Subject.php');

use Tamnza\Database;

// Relationship between student and subject
class InterestedStudent
{
    private int $id = 0;

    public function __construct(
        public Student|null $student = null,
        public Subject|null $subject = null,
    ) {
        // We config the dao
        $this->dao = new Database\BaseDAO(
            'classroom_student_interests',
            'id',
            array('student_id', 'subject_id'),
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
        $fields = array('student_id' => $this->student->getID(), 'subject_id' => $this->subject->getID());

        // If is already created, we update
        if ($this->id == 0) {
            $this->id = $this->dao->insert($fields);
            return  $this->id;
        } else {
            return $this->dao->update($this->id, $fields);
        }
    }

    public static function search(int $id = null, Student $student = null, Subject $subject = null, int $limit = -1): array
    {
        $interested_student = new InterestedStudent();

        $data = $interested_student->dao->select(
            array(
                'id' => $id,
                'student_id' => $student?->getID(),
                'subject_id' => $subject?->getID(),
            ),
            $limit
        );

        // We convert data in array of interested_students
        $result = array();

        foreach ($data as $record) {
            $interested_student = new InterestedStudent();

            $interested_student->setID($record['id']);
            $interested_student->student = Student::getByID($record['student_id']);
            $interested_student->subject = Subject::getByID($record['subject_id']);

            $result[] = $interested_student;
        }

        return $result;
    }

    public static function getByID(int $id): InterestedStudent|null
    {
        $result = InterestedStudent::search(id: $id, limit: 1);

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
