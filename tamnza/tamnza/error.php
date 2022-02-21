<?php

/**
* Error handler, passes flow over the exception logger with new ErrorException.
*/
function log_error($num, $str, $file, $line, $context = null)
{
    log_exception(new ErrorException($str, 0, $num, $file, $line));
}

/**
* Uncaught exception handler.
*/
function log_exception(object $e)
{
    if (DEBUG) {
        print "<div style='text-align: center;'>";
        print "<h2 class='text text-danger'>Exception Occured:</h2>";
        print "<table class='table' style='width: 800px; display: inline-block;'>";
        print "<tr style='background-color:rgb(230,230,230);'><th style='width: 80px;'>Type</th><td>" . get_class($e) . "</td></tr>";
        print "<tr style='background-color:rgb(240,240,240);'><th>Message</th><td>{$e->getMessage()}</td></tr>";
        print "<tr style='background-color:rgb(230,230,230);'><th>File</th><td>{$e->getFile()}</td></tr>";
        print "<tr style='background-color:rgb(240,240,240);'><th>Line</th><td>{$e->getLine()}</td></tr>";
        print "</table></div>";
    } else {
        $message = date("[d/m/Y H:i:s]") . " " . "Type: " . get_class($e) . "; Message: {$e->getMessage()}; File: {$e->getFile()}; Line: {$e->getLine()};";
        file_put_contents(BASE_DIR . "exceptions.log", $message . PHP_EOL, FILE_APPEND);
        header("Location: /?url=" . $GLOBALS['router']->url("error"), true, 301);
    }
    exit();
}

/**
* Checks for a fatal error, work around for set_error_handler not working on fatal errors.
*/
function check_for_fatal()
{
    $error = error_get_last();
    if (isset($error["type"]) && $error["type"] == E_ERROR) {
        log_error($error["type"], $error["message"], $error["file"], $error["line"]);
    }
}

register_shutdown_function("check_for_fatal");
set_error_handler("log_error");
set_exception_handler("log_exception");
ini_set("display_errors", "off");
error_reporting(E_ALL);
