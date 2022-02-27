<?php ob_start() ?>

  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="?url=<?= $GLOBALS['router']->url('quiz_change_list') ?>">My Quizzes</a></li>
      <li class="breadcrumb-item active" aria-current="page"><?= $quiz->name ?></li>
    </ol>
  </nav>
  <h2 class="mb-3">
    <?= $quiz->name ?>
    <a href="?url=<?= $GLOBALS['router']->url('quiz_results', array('pk' => $quiz->getID())) ?>" class="btn btn-primary float-right">View results</a>
  </h2>
  <div class="row mb-3">
    <div class="col-md-6 col-sm-8 col-12">
      <form method="post" novalidate>
        <button type="submit" class="btn btn-success">Save changes</button>
        <a href="?url=<?= $GLOBALS['router']->url('quiz_change_list') ?>" class="btn btn-outline-secondary" role="button">Nevermind</a>
        <a href="?url=<?= $GLOBALS['router']->url('quiz_delete', array('pk' => $quiz->getID())) ?>" class="btn btn-danger float-right">Delete</a>
      </form>
    </div>
  </div>
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-10">
          <strong>Questions</strong>
        </div>
        <div class="col-2">
          <strong>Answers</strong>
        </div>
      </div>
    </div>
    <div class="list-group list-group-flush list-group-formset">
      <?php foreach ($questions as $question) { ?>
        <div class="list-group-item">
          <div class="row">
            <div class="col-10">
              <a href="?url=<?= $GLOBALS['router']->url('question_change', array('quiz_pk' => $quiz->getID(), 'question_pk' => $question->getID())) ?>"><?= $question->text ?></a>
            </div>
            <div class="col-2">
              <?= count($question->answers) ?>
            </div>
          </div>
        </div>
      <?php } ?>
      <?php if (!$questions) { ?>
        <div class="list-group-item text-center">
          <p class="text-muted font-italic mb-0">You haven't created any questions yet. Go ahead and <a href="?url=<?= $GLOBALS['router']->url('question_add', array('quiz_pk' => $quiz->getID())) ?>">add the first question</a>.</p>
        </div>
      <?php } ?>
    </div>
    <div class="card-footer">
      <a href="?url=<?= $GLOBALS['router']->url('question_add', array('quiz_pk' => $quiz->getID())) ?>" class="btn btn-primary btn-sm">Add question</a>
    </div>
  </div>

<?php

$title = 'Tamnza';

$content = ob_get_clean();

require(BASE_DIR . '/views/base.php');

?>