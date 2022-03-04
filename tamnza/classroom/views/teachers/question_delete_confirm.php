<?php ob_start() ?>

  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= $GLOBALS['router']->url('quiz_change_list') ?>">My Quizzes</a></li>
      <li class="breadcrumb-item"><a href="<?= $GLOBALS['router']->url('quiz_change', array('pk' => $quiz->getID())) ?>"><?= $quiz->name ?></a></li>
      <li class="breadcrumb-item"><a href="<?= $GLOBALS['router']->url('question_change', array('quiz_pk' => $quiz->getID(), 'question_pk' => $question->getID())) ?>"><?= $question->text ?></a></li>
      <li class="breadcrumb-item active" aria-current="page">Confirm deletion</li>
    </ol>
  </nav>
  <h2 class="mb-3">Confirm deletion</h2>
  <p class="lead">Are you sure you want to delete the question <strong>"<?= $question->text ?>"</strong>? There is no going back.</p>
  <form method="post">
    <input type="hidden" name="csrftoken" value="WUKMIs4Ny7nXhuFaxv96Qp0JaJ3qgNKNmajDKgbWBSvaaUBeJIAcrCrQsqzwqRMv">
    <button type="submit" class="btn btn-danger btn-lg">Yes, I'm sure</button>
    <a href="<?= $GLOBALS['router']->url('question_change', array('quiz_pk' => $quiz->getID(), 'question_pk' => $question->getID())) ?>" class="btn btn-outline-secondary btn-lg" role="button">Nevermind</a>
  </form>

<?php

$title = 'Tamnza';

$content = ob_get_clean();

require(BASE_DIR . 'views/base.php');

?>
