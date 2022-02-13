<?php

# Project folder
define('BASE_DIR', dirname(__FILE__) . '/../');

// Settings for Tamnza project.


# SECURITY WARNING: don't run with debug turned on in production!
define('DEBUG', true);


# Database
define('DATABASES', array(
    /*'default' => array(
        'ENGINE' => 'sqlite',
        'NAME' => BASE_DIR . 'database.sqlite3'
    ),*/
    'default' => array(
        'ENGINE' => 'mysql',
        'NAME' => 'tamnza',
        'HOST' => 'localhost',
        'PORT' => '3306',
        'USERNAME' => '<username>',
        'PASSWORD' => '<user_password>',
    ),
));

# Internationalization


# Static files (CSS, JavaScript, Images)
define('STATIC_DIR', 'static/');

# We load the error handler
require(dirname(__FILE__) . '/' . 'error.php');
