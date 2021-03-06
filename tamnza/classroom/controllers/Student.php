<?php

namespace Tamnza\App\Classroom\Controller;

require_once(dirname(__FILE__) . '/../models/User.php');
require_once(dirname(__FILE__) . '/../models/Subject.php');
require_once(dirname(__FILE__) . '/../models/StudentAnswer.php');
require_once(dirname(__FILE__) . '/../models/InterestedStudent.php');
require_once(dirname(__FILE__) . '/../models/Answer.php');
require_once(dirname(__FILE__) . '/../models/TakenQuiz.php');

use Tamnza\App\Classroom\Model;

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
            }
            if (!isset($_POST['interests']) || !is_array($_POST['interests']) || count($_POST['interests']) == 0) {
                $errors['interests'] = "This field is required.";
            }

            if (!$errors) {
                // We verify if the account username is free
                $user = Model\User::search(username: $_POST['username']);
                //
                if ($user) {
                    $errors['username'] = 'A user with that username already exists.';
                } else {
                    $user = new Model\User(
                        username: $_POST['username'],
                        password: $_POST['password1']
                    );
                    if ($user->save()) {
                        $student = new Model\Student($user);
                        $student->save();

                        foreach ($_POST['interests'] as $interest_id) {
                            $subject = Model\Subject::getByID(id: $interest_id);

                            if ($subject != null) {
                                $interest = new Model\InterestedStudent($student, $subject);
                                $interest->save();
                            }
                        }

                        // We redirect to the home page
                        $_SESSION['messages']['success'] = 'Your account has been created with success. You can login now';
                        return header("Location: " . $GLOBALS['router']->url("home"), true, 301);
                    }
                }
            }
        }

        $user_type = 'Student';
        $subjects = Model\Subject::search();

        require(BASE_DIR . 'views/registration/signup_form.php');
    }

    public function quizList()
    {
        $user = Model\User::getByID($_SESSION['user']);
        $not_taken_quizzes = array();
        $taken_quizzes = array();

        foreach ($user->student->taken_quizzes as $taken_quiz) {
            $taken_quizzes[] = $taken_quiz->quiz;
        }

        foreach ($user->student->interests as $interest) {
            // We ignore the taken_quizzes
            foreach ($interest->subject->quizzes as $quiz) {
                if (!in_array($quiz, $taken_quizzes)) {
                    $not_taken_quizzes[] = $quiz;
                }
            }
        }

        require(dirname(__FILE__) . '/../views/students/quiz_list.php');
    }

    public function studentInterests()
    {
        $errors = array();
        $user = Model\User::getByID($_SESSION['user']);
        $interest_ids = array();
        foreach ($user->student->interests as $interest) {
            $interest_ids[] = $interest->subject->getID();
        }

        $subjects = Model\Subject::search();

        if ($_POST) {
                // we add new
            foreach ($_POST['interests'] as $interest_id) {
                if (!in_array($interest_id, $interest_ids)) {
                    $subject = Model\Subject::getByID(id: $interest_id);

                    if ($subject != null) {
                        $interest = new Model\InterestedStudent($user->student, $subject);
                        $interest->save();
                    }
                }
            }

                // we remove old
            foreach ($interest_ids as $interest_id) {
                if (!in_array($interest_id, $_POST['interests'])) {
                    $subject = Model\Subject::getByID($interest_id);
                    $interest = Model\InterestedStudent::search(student: $user->student, subject: $subject, limit: 1);

                    if ($interest) {
                        $interest[0]->delete();
                    }
                }
            }

            // We redirect to the home page
            $_SESSION['messages']['success'] = 'Interests updated with success! ';
            return header("Location: " . $GLOBALS['router']->url("quiz_list"), true, 301);
        }

        require(dirname(__FILE__) . '/../views/students/interests_form.php');
    }

    public function takenQuizList()
    {
        $user = Model\User::getByID($_SESSION['user']);
        $taken_quizzes = $user->student->taken_quizzes;

        require(dirname(__FILE__) . '/../views/students/taken_quiz_list.php');
    }

    public function takeQuiz(int $id)
    {
        $errors = array();
        $user = Model\User::getByID($_SESSION['user']);
        $quiz = Model\Quiz::getByID($id);

        if (count(Model\TakenQuiz::search(quiz: $quiz, student: $user->student)) != 0) {
            return header("Location: " . $GLOBALS['router']->url('taken_quiz_list'), true, 301);
        }

        $unanswered_questions = array();
        $answered_questions = array();
        $correct_answers_count = 0;

        foreach ($user->student->quiz_answers as $quiz_answer) {
            if ($quiz_answer->answer->question->quiz->getID() == $quiz->getID()) {
                if (!in_array($quiz_answer->answer->question, $answered_questions)) {
                    $answered_questions[] = $quiz_answer->answer->question;
                }
                if ($quiz_answer->answer->is_correct) {
                    $correct_answers_count++;
                }
            }
        }

        $questions = $quiz->questions;

        if (count($questions) == 0) {
            $_SESSION['messages']['danger'] = 'Sorry!, the quiz ' . $quiz->name . ' is incomplete.';
            return header("Location: " . $GLOBALS['router']->url('quiz_list'), true, 301);
        }

        foreach ($questions as $question) {
            if (count($question->answers) == 0) {
                $_SESSION['messages']['danger'] = 'Sorry!, the quiz ' . $quiz->name . ' is incomplete.';
                return header("Location: " . $GLOBALS['router']->url('quiz_list'), true, 301);
            } elseif (!in_array($question, $answered_questions)) {
                $unanswered_questions[] = $question;
            }
        }

        # -1 because, normally the current question is supposed answer
        $progress = 100 - round(((count($unanswered_questions) - 1) / count($questions)) * 100);

        shuffle($unanswered_questions);
        # If the quiz is not taken, then we have questions to answer
        $question = $unanswered_questions[0];

        if ($_POST) {
            if (!isset($_POST['answer'])) {
                $errors['answer'] = 'This field is required.';
            }
            if (!$errors) {
                $student_answer = new Model\StudentAnswer();
                $student_answer->student = $user->student;
                $student_answer->answer = Model\Answer::getByID($_POST['answer']);

                if ($student_answer->answer->is_correct) {
                    $correct_answers_count++;
                }

                if ($student_answer->save()) {
                    // We verify if the quiz is taken
                    if (count($unanswered_questions) == 1) {
                        $score = round(($correct_answers_count / count($questions)) * 100.0, 2);
                        $taken_quiz = new Model\TakenQuiz(student: $user->student, quiz: $quiz, score: $score);
                        if ($taken_quiz->save()) {
                            if ($score < 50.0) {
                                $_SESSION['messages']['danger'] = 'Better luck next time! Your score for the quiz ' . $quiz->name . ' was ' . $score . '.';
                            } else {
                                $_SESSION['messages']['success'] = 'Congratulations! You completed the quiz ' . $quiz->name . ' with success! You scored ' . $score . ' points.';
                            }
                            return header("Location: " . $GLOBALS['router']->url('quiz_list'), true, 301);
                        }
                    } else {
                        return header("Location: " . $GLOBALS['router']->url('take_quiz', array('pk' => $id)), true, 301);
                    }
                }
            }
        }

        require(dirname(__FILE__) . '/../views/students/take_quiz_form.php');
    }
}
