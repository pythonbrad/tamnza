<?php ob_start() ?>

  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= $GLOBALS['router']->url('quiz_change_list') ?>">My Quizzes</a></li>
      <li class="breadcrumb-item"><a href="<?= $GLOBALS['router']->url('quiz_change', array('pk' => $quiz->getID())) ?>"><?= $quiz->name ?></a></li>
      <li class="breadcrumb-item active" aria-current="page"><?= $question->text ?></li>
    </ol>
  </nav>
  <h2 class="mb-3"><?= $question->text ?></h2>
  <form method="post" novalidate>
    <?php require('question_form.php') ?>
    <div class="card mb-3<?= isset($errors['answer']) ? "border-danger" : "" ?>">
      <div class="card-header">
        <div class="row">
          <div class="col-8">
            <strong>Answers</strong>
          </div>
          <div class="col-2">
            <strong>Correct?</strong>
          </div>
          <div class="col-2">
            <strong>Delete?</strong>
          </div>
        </div>
      </div>
      <?php if (isset($errors['answers'])) { ?>
        <div class="card-body bg-danger border-danger text-white py-2"><?= $errors['answers'] ?></div>
      <?php } ?>
      <?php foreach ($answers as $answer) { ?>
        <div class="list-group list-group-flush list-group-formset">  
          <div class="list-group-item">
            <div class="row">
              <div class="col-8">
                <input type="hidden" name="answer-ids[]" value="<?= $answer->getID() ?>">
                <div id="div_id_answer-text" class="form-group">
                  <label for="id_answer-text" class=" requiredField">
                    Answer<span class="asteriskField">*</span>
                  </label>
                  <div class="">
                    <input type="text" name="answer-<?= $answer->getID() ?>-text" maxlength="255" class="textinput textInput form-control <?= isset($errors["answer-" . $answer->getID() . '-text']) ? 'is-invalid' : '' ?>" id="id_answer-<?= $answer->getID() ?>-text" value="<?= $answer->text ?>">
                    <?php if (isset($errors["answer-" . $answer->getID() . '-text'])) { ?>
                      <p id="error_id_answer-<?= $answer->getID() ?>-text" class="invalid-feedback"><strong>This field is required.</strong></p>
                        <?php if ($answer->text) { ?>
                          <p class="mb-0 mt-1"><small class="text-muted font-italic"><strong>Old answer:</strong> <?= $answer->text ?></small></p>
                        <?php } ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="col-2">
                <input type="checkbox" name="answer-<?= $answer->getID() ?>-is_correct" <?= $answer->is_correct ? "checked" : "" ?>>
              </div>
              <div class="col-2">
                <?php if ($answer->getID() > 0) { ?>
                  <input type="checkbox" name="answer-<?= $answer->getID() ?>-to-delete">
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
    <p>
      <small class="form-text text-muted">Your question may have at least <strong>2</strong> answers and maximum <strong>10</strong> answers. Select at least one correct answer.</small>
    </p>
    <button type="submit" class="btn btn-success">Save changes</button>
    <a href="<?= $GLOBALS['router']->url('quiz_change', array('pk' => $quiz->getID())) ?>" class="btn btn-outline-secondary" role="button">Nevermind</a>
    <a href="<?= $GLOBALS['router']->url('question_delete', array('quiz_pk' => $quiz->getID(), 'question_pk' => $question->getID())) ?>" class="btn btn-danger float-right">Delete</a>
  </form>

<?php

$title = 'Tamnza';

$content = ob_get_clean();

require(BASE_DIR . 'views/base.php');

?>