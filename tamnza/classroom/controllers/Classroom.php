<?php

namespace Tamnza\App\Classroom\Controller;

require_once(dirname(__FILE__) . '/../models/User.php');

class Classroom
{
    public function home()
    {
        if (isset($_SESSION['is_authenticated']) && $_SESSION['is_authenticated']) {
            if ($_SESSION['is_teacher']) {
                 return header("Location: " . $GLOBALS['router']->url("quiz_change_list"), true, 301);
            } else {
                echo "todo";
            }
        } else {
            require(dirname(__FILE__) . '/../views/home.php');
        }
    }

    public function login()
    {
        $errors = array();

        if ($_POST) {
            // We verify if the form is valid
            if (!isset($_POST['username']) || strlen($_POST['username']) == 0) {
                $errors['username'] = "This field is required.";
            }
            if (!isset($_POST['password']) || strlen($_POST['password']) == 0) {
                $errors['password'] = "This field is required.";
            }

            if (!$errors) {
                // We verify if the account credentials is correct
                $user = \Tamnza\App\Classroom\Model\User::search(password: $_POST['password'], username: $_POST['username']);
                //
                if ($user) {
                    // We save the auth
                    $_SESSION['is_authenticated'] = true;
                    $_SESSION['username'] = $user[0]->username;
                    $_SESSION['user'] = $user[0]->getID();
                    $_SESSION['is_teacher'] = $user[0]->is_teacher;

                    // We redirect to the home page
                    return header("Location: " . $GLOBALS['router']->url("home"), true, 301);
                } else {
                    $errors['login'] = "Please enter a correct username and password. Note that both fields may be case-sensitive.";
                }
            }
        }

        require(BASE_DIR . 'views/registration/login.php');
    }

    public function signup()
    {
        require(BASE_DIR . 'views/registration/signup.php');
    }

    public function logout()
    {
        // We clean the session
        $_SESSION = array();

        $_SESSION['messages']['success'] = 'You have been logout with success.';

        // We redirect to the home page
        return header("Location: " . $GLOBALS['router']->url("home"), true, 301);
    }
}
