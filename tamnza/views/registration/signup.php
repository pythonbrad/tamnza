<?php ob_start(); ?>

  <h2>Sign up for a free account</h2>
  <p class="lead">Select below the type of account you want to create</p>
  <a href="?url=/student_signup" class="btn btn-student btn-lg" role="button">I'm a student</a>
  <a href="?url=/teacher_signup" class="btn btn-teacher btn-lg" role="button">I'm a teacher</a>

<?php

$title = 'Tamnza';

$content = ob_get_clean();

require(BASE_DIR . 'views/base.php');

?>