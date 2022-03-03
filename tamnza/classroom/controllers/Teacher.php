<?php

namespace Tamnza\App\Classroom\Controller;

require_once(dirname(__FILE__) . '/../models/User.php');
require_once(dirname(__FILE__) . '/../models/Subject.php');
require_once(dirname(__FILE__) . '/../models/Quiz.php');
require_once(dirname(__FILE__) . '/../models/Question.php');
require_once(dirname(__FILE__) . '/../models/Answer.php');

class Teacher
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

            if (!$errors) {
                // We verify if the account username is free
                $user = \Tamnza\App\Classroom\Model\User::search(username: $_POST['username']);
                //
                if ($user) {
                    $errors['username'] = 'A user with that username already exists.';
                } else {
                    $user = new \Tamnza\App\Classroom\Model\User(
                        username: $_POST['username'],
                        password: $_POST['password1'],
                        is_teacher: true,
                    );
                    if ($user->save()) {
                        // We redirect to the home page
                        $_SESSION['messages']['success'] = 'Your account has been created with success. You can login now';
                        return header("Location: " . $GLOBALS['router']->url("home"), true, 301);
                    }
                }
            }
        }

        $user_type = 'Teacher';

        require(BASE_DIR . 'views/registration/signup_form.php');
    }

    public function quizChangeList()
    {
        $user = \Tamnza\App\Classroom\Model\User::getByID($_SESSION['user']);
        $quizzes = $user->quizzes;
        require(dirname(__FILE__) . '/../views/teacher/quiz_change_list.php');
    }

    public function quizAdd()
    {
        $errors = array();

        if ($_POST) {
            // We verify if the form is valid
            if (!isset($_POST['name']) || strlen($_POST['name']) == 0) {
                $errors['name'] = "This field is required.";
            }
            if (!isset($_POST['subject']) || strlen($_POST['subject']) == 0) {
                $errors['subject'] = "This field is required.";
            }

            if (!$errors) {
                $quiz = new \Tamnza\App\Classroom\Model\Quiz(
                    name: $_POST['name'],
                    owner: \Tamnza\App\Classroom\Model\User::getByID($_SESSION['user']),
                    subject: \Tamnza\App\Classroom\Model\Subject::getByID($_POST['subject']),
                );

                if ($quiz->save()) {
                    // We redirect to the home page
                    $_SESSION['messages']['success'] = 'The quiz was created with success! Go ahead and add some questions now.';
                    return header("Location: " . $GLOBALS['router']->url(
                        "quiz_change",
                        array('pk' => $quiz->getID())
                    ), true, 301);
                }
            }
        }

        $subjects = \Tamnza\App\Classroom\Model\Subject::search();
        require(dirname(__FILE__) . '/../views/teacher/quiz_add_form.php');
    }

    public function quizChange(int $id)
    {
        $errors = array();
        $quiz = \Tamnza\App\Classroom\Model\Quiz::getByID($id);

        if ($_POST) {
            // We verify if the form is valid
            if (!isset($_POST['name']) || strlen($_POST['name']) == 0) {
                $errors['name'] = "This field is required.";
            }
            if (!isset($_POST['subject']) || strlen($_POST['subject']) == 0) {
                $errors['subject'] = "This field is required.";
            }

            if (!$errors) {
                $quiz->name = $_POST['name'];
                $quiz->subject = \Tamnza\App\Classroom\Model\Subject::getByID($_POST['subject']);

                if ($quiz->save()) {
                    // We redirect to the home page
                    $_SESSION['messages']['success'] = 'The quiz was updated with success!';
                    return header("Location: " . $GLOBALS['router']->url(
                        "quiz_change",
                        array('pk' => $quiz->getID())
                    ), true, 301);
                }
            }
        }

        $questions = $quiz->questions;
        $subjects = \Tamnza\App\Classroom\Model\Subject::search();

        require(dirname(__FILE__) . '/../views/teacher/quiz_change_form.php');
    }

    public function quizResults(int $id)
    {
        $quiz = \Tamnza\App\Classroom\Model\Quiz::getByID($id);
        $taken_quizzes = $quiz->taken_quizzes;
        $total_score = 0.0;
        foreach ($taken_quizzes as $taken_quiz) {
            $total_score = $taken_quiz->score;
        }
        $average_score = $total_score ? ($total_score / count($taken_quizzes)) : $total_score;
        require(dirname(__FILE__) . '/../views/teacher/quiz_results.php');
    }

    public function quizDelete(int $id)
    {
        $errors = array();
        $quiz = \Tamnza\App\Classroom\Model\Quiz::getByID($id);

        if ($_POST) {
            // to secure
            $_SESSION['messages']['success'] = 'The quiz ' . $quiz->name . ' was deleted with success!';
            if ($quiz->delete()) {
                return header("Location: " . $GLOBALS['router']->url("quiz_change_list"), true, 301);
            }
        }

        require(dirname(__FILE__) . '/../views/teacher/quiz_delete_confirm.php');
    }

    public function questionAdd(int $quiz_id)
    {
        $errors = array();
        $quiz = \Tamnza\App\Classroom\Model\Quiz::getByID($quiz_id);

        if ($_POST) {
            if (!isset($_POST['text']) || strlen($_POST['text']) == 0) {
                $errors['text'] = "This field is required.";
            }

            if (!$errors) {
                // to secure
                $question = new \Tamnza\App\Classroom\Model\Question(text: $_POST['text'], quiz: $quiz);
                if ($question->save()) {
                    $_SESSION['messages']['success'] = 'You may now add answers/options to the question.';
                    return header("Location: " . $GLOBALS['router']->url("question_change", array('quiz_pk' => $quiz_id, 'question_pk' => $question->getID())), true, 301);
                }
            }
        }

        require(dirname(__FILE__) . '/../views/teacher/question_add_form.php');
    }

    public function questionChange(int $quiz_id, int $question_id)
    {
        $errors = array();
        $quiz = \Tamnza\App\Classroom\Model\Quiz::getByID($quiz_id);
        $question = \Tamnza\App\Classroom\Model\Question::getByID($question_id);
        $answers = array();
        $to_ignore = array();

        foreach ($question->answers as $answer) {
            if (($_POST['answer-' . $answer->getID() . '-to-delete'] ?? '') == 'on') {
                $answer->delete();
                $to_ignore[] = $answer->getID();
            } else {
                $answers[] = $answer;
            }
        }

        $length = count($answers);

        for ($i = 0; $i < 3 && ($length + $i) < 10; $i++) {
            $answer_id = -($i + 1);
            $answer = new \Tamnza\App\Classroom\Model\Answer();
            $answer->setID($answer_id);
            $answer->text = $_POST['answer-' . $answer_id . '-text'] ?? '';
            $answer->is_correct = ($_POST['answer-' . $answer_id . '-is_correct'] ?? '') == 'on';
            $answers[] = $answer;
        }

        if ($_POST) {
            if (!isset($_POST['text']) || strlen($_POST['text']) == 0) {
                $errors['text'] = "This field is required.";
            }
            // We should verify that we have at least 2 answers and 1 correct
            if (isset($_POST['answer-ids'])) {
                $correct_answers_num = 0;
                $answers_num = 0;
                foreach ($_POST['answer-ids'] as $answer_id) {
                    if (($_POST['answer-' . $answer_id . '-is_correct'] ?? '') == 'on' && !in_array($answer_id, $to_ignore)) {
                        $correct_answers_num++;
                        if (($_POST['answer-' . $answer_id . '-text'] ?? '') == '') {
                            $errors['answer-' . $answer_id . '-text'] = "This field is required.";
                        } else {
                            $answers_num++;
                        }
                    } else if (($_POST['answer-' . $answer_id . '-text'] ?? '') != '') {
                        $answers_num++;
                    }
                }
                if ($correct_answers_num < 1) {
                    $errors['answers'] = "Mark at least one answer as correct.";
                } else if ($answers_num < 2) {
                    $errors['answers'] = "Please submit at least 2 forms.";
                }
            } else {
                $errors['answers'] = "Please submit at least 2 forms.";
            }

            if (!$errors) {
                // to secure
                $question->text = $_POST['text'];
                if ($question->save()) {
                    foreach ($_POST['answer-ids'] as $answer_id) {
                        if ($_POST['answer-' . $answer_id . '-text']) {
                            $answer = new \Tamnza\App\Classroom\Model\Answer();
                            $answer->text = $_POST['answer-' . $answer_id . '-text'];
                            $answer->is_correct = ($_POST['answer-' . $answer_id . '-is_correct'] ?? null) == 'on';
                            $answer->question = $question;
                            // negative, to create
                            // positive, to update if not delete else delete
                            if ($answer_id > 0) {
                                $answer->setID($answer_id);
                            }
                            $answer->save();
                        }
                    }
                    $_SESSION['messages']['success'] = 'Question and answers saved with success!';
                    return header("Location: " . $GLOBALS['router']->url("quiz_change", array('pk' => $quiz_id)), true, 301);
                }
            }
        }

        require(dirname(__FILE__) . '/../views/teacher/question_change_form.php');
    }

    public function questionDelete(int $quiz_id, int $question_id)
    {
        $errors = array();
        $question = \Tamnza\App\Classroom\Model\Question::getByID($question_id);
        $quiz = $question->quiz;

        if ($_POST) {
            // to secure
            $_SESSION['messages']['success'] = 'The question ' . $question->text . ' was deleted with success!';
            if ($question->delete()) {
                return header("Location: " . $GLOBALS['router']->url("quiz_change", array('pk' => $quiz_id)), true, 301);
            }
        }

        require(dirname(__FILE__) . '/../views/teacher/question_delete_confirm.php');
    }
}
