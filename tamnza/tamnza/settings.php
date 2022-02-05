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
        'HOST' => '0.0.0.0',
        'PORT' => '3306',
        'USERNAME' => 'root',
        'PASSWORD' => 'pass',
    ),
));

# Internationalization


# Static files (CSS, JavaScript, Images)
define('STATIC_DIR', 'static/');

# We load the error handler
include(dirname(__FILE__) . '/' . 'error.php');
