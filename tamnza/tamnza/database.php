<?php

namespace Tamnza\Database;

// Database Access Object
class BaseDAO
{
    private $db;

    public function __construct()
    {
        $this->db = match (DATABASES["default"]['ENGINE']) {
            "mysql" => new \PDO("mysql:host=" . DATABASES["default"]["HOST"] . ";port=" . DATABASES["default"]["PORT"] . ";dbname=" . DATABASES["default"]["NAME"], DATABASES["default"]["USERNAME"], DATABASES["default"]["PASSWORD"]),
            "sqlite" => new \PDO("sqlite:" . DATABASES["default"]["NAME"]),
        };
    }
}
