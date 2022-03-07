<?php ob_start() ?>

  <div class="progress mb-3">
    <div class="progress-bar" role="progressbar" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $progress ?>%"></div>
  </div>
  <h2 class="mb-3"><?= htmlentities($quiz->name) ?></h2>
  <p class="lead"><?= htmlentities($question->text) ?></p>
  <form method="post" novalidate>
    <div id="div_id_answer" class="form-group">
      <label class=" requiredField">Answer
        <span class="asteriskField">*</span>
      </label>
      <div>
        <?php foreach ($question->answers as $answer) { ?>
          <div class="form-check">
            <input type="radio" class="form-check-input" name="answer" value="<?= $answer->getID() ?>" id="id_answer_<?= $answer->getID() ?>" required="">
            <label class="form-check-label" for="id_answer_<?= $answer->getID() ?>">
              <?= htmlentities($answer->text) ?>
            </label>
          </div>
        <?php } ?>
        <?php if (isset($errors['answer'])) { ?>
          <p id="error_1_id_answer" class="invalid-feedback"><strong><?= $errors['answer'] ?></strong></p>
        <?php } ?>

      </div>
    </div>

    <button type="submit" class="btn btn-primary">Next →</button>
  </form>

<?php

$title = 'Tamnza';

$content = ob_get_clean();

require(BASE_DIR . 'views/base.php');

?>