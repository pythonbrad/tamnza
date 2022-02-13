<?php

namespace Tamnza\App\Classroom\Controller;

require_once(dirname(__FILE__) . '/../models/User.php');

class Classroom
{
    public function home()
    {
        require(dirname(__FILE__) . '/../views/home.php');
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
                    $_SESSION['is_teacher'] = $user[0]->is_teacher;

                    // We redirect to the home page
                    header("Location: /", true, 301);
                } else {
                    $errors['login'] = "Please enter a correct username and password. Note that both fields may be case-sensitive.";
                }
            }
        }

        require(BASE_DIR . 'views/registration/login.php');
    }
}
