<?php

namespace Tamnza\Database;

// Database Access Object
class BaseDAO
{
    private static ?\PDO $db = null;

    public function __construct(
        private string $tb_name,
        private string $pk,
        private array $fields,
    ) {
        if (!self::$db) {
            self::$db = match (DATABASES["default"]['ENGINE']) {
                "mysql" => new \PDO("mysql:host=" . DATABASES["default"]["HOST"] . ";port=" . DATABASES["default"]["PORT"] . ";dbname=" . DATABASES["default"]["NAME"], DATABASES["default"]["USERNAME"], DATABASES["default"]["PASSWORD"]),
                "sqlite" => new \PDO("sqlite:" . DATABASES["default"]["NAME"]),
            };
        }
    }

    // This method verify the validity of the fields given
    private function fieldsVerification(array $fields)
    {
        foreach (array_keys($fields) as $field) {
            if (!in_array($field, $this->fields) && $field != $this->pk) {
                throw new \Exception("$field not found in the fields of table " . $this->tb_name);
            }
        }
    }

    public function insert(array $fields): int
    {
        $this->fieldsVerification($fields);

        // We build the query
        $sql = 'INSERT INTO ' . $this->tb_name . '(' . join(',', array_keys($fields)) . ') VALUES (' . trim(str_repeat('?,', count($fields)), ',') . ');';

        // We execute the query
        self::$db->prepare($sql)->execute(array_values($fields));

        // We get his id
        $sql = 'SELECT LAST_INSERT_ID();';

        return self::$db->query($sql)->fetch()[0];
    }

    public function update(int $id, array $fields): bool
    {
        $this->fieldsVerification($fields);

        if (count($fields) > 0) {
            // We build the query
            $sql = 'UPDATE ' . $this->tb_name . ' SET ';

            $sql .= join('=?,', array_keys($fields)) . '=?';

            $sql = trim($sql, ',') . ' WHERE ' . $this->pk . '=' . $id . ';';

            // We execute the query
            return self::$db->prepare($sql)->execute(array_values($fields));
        }

        return false;
    }

    public function select(array $fields, int $limit): array
    {
        // We remove null data
        $fields = array_filter($fields, function ($var) {
            return !is_null($var);
        });

        $this->fieldsVerification($fields);

        // We build the query
        $sql = 'SELECT * FROM ' . $this->tb_name . ((count($fields) > 0) ? ' WHERE ' : '');

        foreach ($fields as $field => $value) {
            $sql .= $field . '=? AND ';
        }

        $sql = trim($sql, 'AND ') . (($limit > -1) ? (' LIMIT ' . $limit) : '') . ';';

        // We execute the query
        $stmt =  self::$db->prepare($sql);
        $stmt->execute(array_values($fields));

        // We will got an error if the data are too large
        // https://stackoverflow.com/questions/33250453/how-to-solve-general-error-2006-mysql-server-has-gone-away
        // a temporary solution is to use limit
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function delete(int $id)
    {
        // We build the query
        $sql = 'DELETE FROM ' . $this->tb_name . ' WHERE ' . $this->pk . '=' . $id . ';';
        return self::$db->prepare($sql)->execute();
    }
}
