<?php

namespace Tamnza\App\Classroom\Model;

require_once('Student.php');
require_once('Quiz.php');

// Relationship between student and quiz
class TakenQuiz
{
    private int $id = 0;
    public \Datetime $date;

    public function __construct(
        public int $score = 0,
        public Student|null $student = null,
        public Quiz|null $quiz = null,
    ) {
        $this->date = new \Datetime();

        // We config the dao
        $this->dao = new \Tamnza\Database\BaseDAO(
            'classroom_takenquiz',
            'id',
            array('score', 'date', 'student_id', 'quiz_id'),
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
        $fields = array('score' => $this->score, 'date' => $this->date->format('Y-m-d H:i:s'), 'student_id' => $this->student->getID(), 'quiz_id' => $this->quiz->getID());

        // If is already created, we update
        if ($this->id == 0) {
            $this->id = $this->dao->insert($fields);
            return  $this->id;
        } else {
            return $this->dao->update($this->id, $fields);
        }
    }

    public static function search(int $id = null, int $score = null, \Datetime $date = null, Student $student = null, Quiz $quiz = null, int $limit = -1): array
    {
        $taken_quiz = new TakenQuiz();

        $data = $taken_quiz->dao->select(
            array(
                'id' => $id,
                'score' => $score,
                'student_id' => $student?->getID(),
                'quiz_id' => $quiz?->getID(),
            ),
            $limit
        );

        // We convert data in array of taken_quizs
        $result = array();

        foreach ($data as $record) {
            $taken_quiz = new TakenQuiz(score: $record['score']);

            $taken_quiz->date = \Datetime::createFromFormat('Y-m-d H:i:s', $record['date']);
            $taken_quiz->setID($record['id']);
            $taken_quiz->student = Student::getByID($record['student_id']);
            $taken_quiz->quiz = Quiz::getByID($record['quiz_id']);

            $result[] = $taken_quiz;
        }

        return $result;
    }

    public static function getByID(int $id): TakenQuiz|null
    {
        $result = TakenQuiz::search(id: $id, limit: 1);

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
