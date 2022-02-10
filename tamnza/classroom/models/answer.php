<?php

namespace Tamnza\App\Classroom\Model;

require_once('question.php');

class Answer
{
    private int $id = 0;

    public function __construct(
        public string $text = '',
        public bool $is_correct = false,
        public Question|null $question = null,
    ) {
        // We config the dao
        $this->dao = new \Tamnza\Database\BaseDAO(
            'classroom_answer',
            'id',
            array('text', 'is_correct', 'question_id'),
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
        $fields = array('text' => $this->text, 'is_correct' => $this->is_correct ? 1 : 0, 'question_id' => $this->question->getID());

        // If is already created, we update
        if ($this->id == 0) {
            $this->id = $this->dao->insert($fields);
            return $this->id;
        } else {
            return $this->dao->update($this->id, $fields);
        }
    }

    public static function search(int $id = null, string $text = null, bool $is_correct = null, Question $question = null, int $limit = -1): array
    {
        $answer = new Answer();

        $data = $answer->dao->select(
            array(
                'id' => $id,
                'text' => $text,
                'is_correct' => is_null($is_correct) ? null : ($is_correct ? 1 : 0),
                'question_id' => $question?->getID(),
            ),
            $limit
        );

        // We convert data in array of answers
        $result = array();

        foreach ($data as $record) {
            $answer = new Answer(text: $record['text'], is_correct: $record['is_correct']);

            $answer->setID($record['id']);
            $answer->question = Question::getByID($record['question_id']);

            $result[] = $answer;
        }

        return $result;
    }

    public static function getByID(int $id): Answer|null
    {
        $result = Answer::search(id: $id, limit: 1);

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
