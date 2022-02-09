<?php

namespace Tamnza\App\Classroom\Model;

class Subject
{
    private int $id = 0;

    public function __construct(
        public string $name = '',
        public string $color = '',
    ) {
        // We config the dao
        $this->dao = new \Tamnza\Database\BaseDAO(
            'classroom_subject',
            'id',
            array('name', 'color'),
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
        $fields = array('name' => $this->name, 'color' => $this->color);

        // If is already created, we update
        if ($this->id == 0) {
            $this->id = $this->dao->insert($fields);
            return $this->id;
        } else {
            return $this->dao->update($this->id, $fields);
        }
    }

    public static function search(int $id = null, string $name = null, string $color = null, int $limit = -1): array
    {
        $subject = new Subject();

        $data = $subject->dao->select(
            array(
                'id' => $id,
                'name' => $name,
                'color' => $color,
            ),
            $limit
        );

        // We convert data in array of subjects
        $result = array();

        foreach ($data as $record) {
            $subject = new Subject(name: $record['name'], color: $record['color']);

            $subject->setID($record['id']);

            $result[] = $subject;
        }

        return $result;
    }

    public static function getByID(int $id): Subject|null
    {
        $result = Subject::search(id: $id, limit: 1);

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
