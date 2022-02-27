<?php ob_start() ?>

  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="url=<?= $GLOBALS['router']->url('quiz_change_list') ?>">My Quizzes</a></li>
      <li class="breadcrumb-item"><a href="url=<?= $GLOBALS['router']->url('quiz_change', array('pk' => $quiz->getID())) ?>"><?= $quiz->name ?></a></li>
      <li class="breadcrumb-item active" aria-current="page">Results</li>
    </ol>
  </nav>
  <h2 class="mb-3"><?= $quiz->name ?> Results</h2>

  <div class="card">
    <div class="card-header">
      <strong>Taken Quizzes</strong>
      <span class="badge badge-pill badge-primary float-right">Average Score: <?= $quiz->averageScore() ?></span>
    </div>
    <table class="table mb-0">
      <thead>
        <tr>
          <th>Student</th>
          <th>Date</th>
          <th>Score</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($taken_quizzes as $taken_quiz) { ?>
          <tr>
            <td><?= $taken_quiz->student->user->username ?></td>
            <td><?= $taken_quiz->date ?></td>
            <td><?= $taken_quiz->score ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
    <div class="card-footer text-muted">
      Total respondents: <strong><?= count($taken_quizzes) ?></strong>
    </div>
  </div>

<?php

$title = 'Tamnza';

$content = ob_get_clean();

require(BASE_DIR . 'views/base.php');

?>