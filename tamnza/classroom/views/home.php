<?php ob_start(); ?>

  <h2>Welcome to the Tamnza! <span class="icon-emo-happy"></span></h2>
  <p class="lead">
    If you already have an account, go ahead and <a href="?url=/login">log in</a>. If you are new to Tamnza, get started
    by creating a <a href="?url=<?= $GLOBALS['router']->url("student_signup") ?>">student account</a> or a <a href="?url=<?= $GLOBALS['router']->url("teacher_signup") ?>">teacher account</a>.
  </p>
  <hr>
  <h3>What's this about?</h3>
  <p>
    This application is an project of <a href="https://resulam.com">Resulam</a>.
    In this application, users can sign up as a student or a teacher. Teachers can create quizzes and students can answer quizzes
    based on their interests.
  </p>

<?php

$title = "Tamnza";

$content = ob_get_clean();

require(BASE_DIR . '/views/base.php');

?>
