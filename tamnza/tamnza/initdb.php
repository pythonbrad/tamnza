<?php

/*
	This script permit to initialize the database
*/

require 'autoloader.php';

require dirname(__FILE__) . '/../classroom/initdb.php';

require dirname(__FILE__) . '/../classroom/initdummydata.php';

echo 'Database initialized!';