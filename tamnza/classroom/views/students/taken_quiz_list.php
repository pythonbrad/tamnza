<?php ob_start() ?>

<?php

$active = 'taken';

require('_header.php')

?>

  <div class="card">
    <table class="table mb-0">
      <thead>
        <tr>
          <th>Quiz</th>
          <th>Subject</th>
          <th>Score</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($taken_quizzes as $taken_quiz) { ?>
          <tr>
            <td><?= htmlentities($taken_quiz->quiz->name) ?></td>
            <td><?= $taken_quiz->quiz->subject->getHtmlBadge() ?></td>
            <td><?= $taken_quiz->score ?></td>
          </tr>
        <?php } ?>
        <?php if (count($taken_quizzes) == 0) { ?>
          <tr>
            <td class="bg-light text-center font-italic" colspan="3">You haven't completed any quiz yet.</td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

<?php

$title = 'Tamnza';

$content = ob_get_clean();

require(BASE_DIR . 'views/base.php');

?>