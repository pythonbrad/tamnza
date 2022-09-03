<?php

# Project folder
define('BASE_DIR', dirname(__FILE__) . '/../');

// Settings for Tamnza project.


# SECURITY WARNING: don't run with debug turned on in production!
define('DEBUG', false);


# Database
define('DATABASES', array(
    /*'default' => array(
        'ENGINE' => 'sqlite',
        'NAME' => BASE_DIR . 'database.sqlite3'
    ),*/
    'default' => array(
        'ENGINE' => 'mysql',
        'NAME' => $_ENV['MYSQL_DATABASE'],
        'HOST' => $_ENV['MYSQL_HOST'],
        'PORT' => '3306',
        'USERNAME' => $_ENV['MYSQL_USERNAME'],
        'PASSWORD' => $_ENV['MYSQL_PASSWORD'],
    ),
));

# Internationalization


# Static files (CSS, JavaScript, Images)
define('STATIC_DIR', 'static/');

# We load the error handler
require(dirname(__FILE__) . '/' . 'error.php');
