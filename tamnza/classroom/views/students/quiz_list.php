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
        <?php foreach ($quizzes as $quiz) { ?>
          <tr>
            <td class="align-middle"><?= htmlentities($quiz->name) ?></td>
            <td class="align-middle"><?= $quiz->subject->getHtmlBadge() ?></td>
            <td class="align-middle"><?= count($quiz->questions) ?> questions</td>
            <td class="text-right">
              <a href="<?= $GLOBALS['router']->url('take_quiz', array('pk' => $quiz->getID())) ?>" class="btn btn-primary">Start quiz</a>
            </td>
          </tr>
        <?php } ?>
        <?php if (count($quizzes) == 0) { ?>
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