<?php

namespace Tamnza\App\Classroom\Model;

require_once('User.php');
require_once('Subject.php');
require_once('Quiz.php');
require_once('Question.php');

use Tamnza\Database;

class Quiz
{
    private int $id = 0;

    public function __construct(
        public string $name = '',
        public User|null $owner = null,
        public Subject|null $subject = null,
    ) {
        // We config the dao
        $this->dao = new Database\BaseDAO(
            'classroom_quiz',
            'id',
            array('name', 'owner_id', 'subject_id'),
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
    public function __get(string $key): array
    {
        return match ($key) {
            'questions' => Question::search(quiz: $this),
            'taken_quizzes' => TakenQuiz::search(quiz: $this),
            default => throw new \Exception("$key is not a special attribute"),
        };
    }

    // We will get the avg score
    public function averageScore(): float
    {
        $total_score = 0.0;
        $taken_quizzes = $this->taken_quizzes;

        foreach ($taken_quizzes as $taken_quiz) {
            $total_score = $taken_quiz->score;
        }

        return $total_score ? ($total_score / count($taken_quizzes)) : $total_score;
    }

    public function save(): bool
    {
        $fields = array('name' => $this->name, 'owner_id' => $this->owner->getID(), 'subject_id' => $this->subject->getID());

        // If is already created, we update
        if ($this->id == 0) {
            $this->id = $this->dao->insert($fields);
            return  $this->id;
        } else {
            return $this->dao->update($this->id, $fields);
        }
    }

    public static function search(int $id = null, string $name = null, User $owner = null, Subject $subject = null, int $limit = -1): array
    {
        $quiz = new Quiz();

        $data = $quiz->dao->select(
            array(
                'id' => $id,
                'name' => $name,
                'owner_id' => $owner?->getID(),
                'subject_id' => $subject?->getID(),
            ),
            $limit
        );

        // We convert data in array of quizzes
        $result = array();

        foreach ($data as $record) {
            $quiz = new Quiz(name: $record['name']);

            $quiz->setID($record['id']);
            $quiz->owner = User::getByID($record['owner_id']);
            $quiz->subject = Subject::getByID($record['subject_id']);

            $result[] = $quiz;
        }

        return $result;
    }

    public static function getByID(int $id): Quiz|null
    {
        $result = Quiz::search(id: $id, limit: 1);

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
