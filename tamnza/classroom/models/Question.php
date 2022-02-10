<?php

namespace Tamnza\App\Classroom\Model;

require_once('Quiz.php');

class Question
{
    private int $id = 0;

    public function __construct(
        public string $text = '',
        public Quiz|null $quiz = null,
    ) {
        // We config the dao
        $this->dao = new \Tamnza\Database\BaseDAO(
            'classroom_question',
            'id',
            array('text', 'quiz_id'),
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
        $fields = array('text' => $this->text, 'quiz_id' => $this->quiz->getID());

        // If is already created, we update
        if ($this->id == 0) {
            $this->id = $this->dao->insert($fields);
            return $this->id;
        } else {
            return $this->dao->update($this->id, $fields);
        }
    }

    public static function search(int $id = null, string $text = null, Quiz $quiz = null, int $limit = -1): array
    {
        $question = new Question();

        $data = $question->dao->select(
            array(
                'id' => $id,
                'text' => $text,
                'quiz_id' => $quiz?->getID(),
            ),
            $limit
        );

        // We convert data in array of questions
        $result = array();

        foreach ($data as $record) {
            $question = new Question(text: $record['text']);

            $question->setID($record['id']);
            $question->quiz = Quiz::getByID($record['quiz_id']);

            $result[] = $question;
        }

        return $result;
    }

    public static function getByID(int $id): Question|null
    {
        $result = Question::search(id: $id, limit: 1);

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
