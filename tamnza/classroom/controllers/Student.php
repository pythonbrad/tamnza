<?php

namespace Tamnza\App\Classroom\Controller;

require_once(dirname(__FILE__) . '/../models/User.php');
require_once(dirname(__FILE__) . '/../models/Subject.php');

class Student
{
    public function signup()
    {
        $errors = array();

        if ($_POST) {
            // We verify if the form is valid
            if (!isset($_POST['username']) || strlen($_POST['username']) == 0) {
                $errors['username'] = "This field is required.";
            }
            if (!isset($_POST['password1']) || strlen($_POST['password1']) == 0) {
                $errors['password1'] = "This field is required.";
            }
            if (!isset($_POST['password2']) || strlen($_POST['password2']) == 0) {
                $errors['password2'] = "This field is required.";
            } else if ($_POST['password1'] != $_POST['password2']) {
                $errors['password2'] = "The two password fields didn’t match.";
            } else if (!$_POST['interests']) {
                $errors['password2'] = "This field is required.";
            }
            if (!isset($_POST['interests']) || count($_POST['interests']) == 0) {
                $errors['interests'] = "This field is required.";
            }

            if (!$errors) {
                // We verify if the account username is free
                $user = \Tamnza\App\Classroom\Model\User::search(username: $_POST['username']);
                //
                if ($user) {
                    $errors['username'] = 'A user with that username already exists.';
                } else {
                    $user = new \Tamnza\App\Classroom\Model\User(
                        username: $_POST['username'],
                        password: $_POST['password1']
                    );
                    if ($user->save()) {
                        $student = new \Tamnza\App\Classroom\Model\Student($user);
                        $student->save();

                        foreach ($_POST['interests'] as $interest_id) {
                            $subject = \Tamnza\App\Classroom\Model\Subject::getByID(id: $interest_id);

                            if ($subject != null) {
                                $interest = new \Tamnza\App\Classroom\Model\InterestedStudent($student, $subject);
                                $interest->save();
                            }
                        }

                        // We redirect to the home page
                        $_SESSION['messages']['success'] = 'Your account has been created with success. You can login now';
                        header("Location: /?url=" . $GLOBALS['router']->url("home"), true, 301);
                        return;
                    }
                }
            }
        }

        $user_type = 'Student';
        $interests = \Tamnza\App\Classroom\Model\Subject::search();

        require(BASE_DIR . 'views/registration/signup_form.php');
    }
}
