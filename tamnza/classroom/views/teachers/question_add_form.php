<?php ob_start() ?>

  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= $GLOBALS['router']->url('quiz_change_list') ?>">My Quizzes</a></li>
      <li class="breadcrumb-item"><a href="<?= $GLOBALS['router']->url('quiz_change', array('pk' => $quiz->getID())) ?>"><?= htmlentities($quiz->name) ?></a></li>
      <li class="breadcrumb-item active" aria-current="page">Add a new question</li>
    </ol>
  </nav>
  <h2 class="mb-3">Add a new question</h2>
  <p class="lead">Add first the text of the question. In the next step you will be able to add the possible answers.</p>
  <form method="post" novalidate>
    <?php require('question_form.php') ?>
    <button type="submit" class="btn btn-success">Save</button>
    <a href="<?= $GLOBALS['router']->url('quiz_change', array('pk' => $quiz->getID())) ?>" class="btn btn-outline-secondary" role="button">Nevermind</a>
  </form>

<?php

$title = 'Tamnza';

$content = ob_get_clean();

require(BASE_DIR . 'views/base.php');

?>