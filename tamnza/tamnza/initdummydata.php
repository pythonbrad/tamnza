<?php

/*
	This script permit to load some dummy data to permit to fast test the application
*/

require 'autoloader.php';

$db = new Tamnza\Database\BaseDAO('', '', []);

$db->exec("
INSERT INTO classroom_subject (id, name, color) VALUES (null, \"Nufi\", \"green\");
INSERT INTO classroom_subject (id, name, color) VALUES (null, \"Yemba\", \"orange\");
INSERT INTO classroom_subject (id, name, color) VALUES (null, \"Ghomala\", \"blue\");
");