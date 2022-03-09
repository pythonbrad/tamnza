<?php ob_start() ?>
  
<?php

$active = 'new';

require('_header.php')

?>

  <div class="card">
    <table class="table mb-0">
      <thead>
        <tr>
          <th>Quiz</th>
          <th>Subject</th>
          <th>Length</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($not_taken_quizzes as $not_taken_quiz) { ?>
          <tr>
            <td class="align-middle"><?= htmlentities($not_taken_quiz->name) ?></td>
            <td class="align-middle"><?= $not_taken_quiz->subject->getHtmlBadge() ?></td>
            <td class="align-middle"><?= count($not_taken_quiz->questions) ?> questions</td>
            <td class="text-right">
              <a href="<?= $GLOBALS['router']->url('take_quiz', array('pk' => $not_taken_quiz->getID())) ?>" class="btn btn-primary">Start quiz</a>
            </td>
          </tr>
        <?php } ?>
        <?php if (count($not_taken_quizzes) == 0) { ?>
          <tr>
            <td class="bg-light text-center font-italic" colspan="4">No quiz matching your interests right now.</td>
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