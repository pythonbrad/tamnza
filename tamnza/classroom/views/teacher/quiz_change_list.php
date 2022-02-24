<?php ob_start(); ?>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">My Quizzes</li>
    </ol>
  </nav>
  <h2 class="mb-3">My Quizzes</h2>
  <a href="?url=<?= $GLOBALS['router']->url("quiz_add") ?>" class="btn btn-primary mb-3" role="button">Add quiz</a>
  <div class="card">
    <table class="table mb-0">
      <thead>
        <tr>
          <th>Quiz</th>
          <th>Subject</th>
          <th>Questions</th>
          <th>Taken</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($quizzes as $quiz) { ?>
          <tr>
            <td class="align-middle"><a href="?url=<?= $GLOBALS['router']->url("quiz_change", array('pk' => $quiz->getID())) ?>"><?= $quiz->name ?></a></td>
            <td class="align-middle"><?= $quiz->subject->getHtmlBadge() ?></td>
            <td class="align-middle"><?= count($quiz->questions) ?></td>
            <td class="align-middle"><?= count($quiz->taken_quizzes) ?></td>
            <td class="text-right">
              <a href="?url=<?= $GLOBALS['router']->url("quiz_results", array('pk' => $quiz->getID())) ?>" class="btn btn-primary">View results</a>
            </td>
          </tr>
        <?php } ?>
        <?php if (count($quizzes) == 0) { ?>
          <tr>
            <td class="bg-light text-center font-italic" colspan="5">You haven't created any quiz yet.</td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
<?php

$title = "Tamnza";

$content = ob_get_clean();

require(BASE_DIR . '/views/base.php');

?>
