<?php ob_start() ?>

  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="?url=<?= $GLOBALS['router']->url('quiz_change_list') ?>">My Quizzes</a></li>
      <li class="breadcrumb-item active" aria-current="page">Add a new quiz</li>
    </ol>
  </nav>
  <h2 class="mb-3">Add a new quiz</h2>
  <div class="row">
    <div class="col-md-6 col-sm-8 col-12">
      <form method="post" novalidate>
        <div id="div_id_name" class="form-group">
          <label for="id_name" class=" requiredField">
            Name<span class="asteriskField">*</span>
          </label>
          <div class="">
            <input name="name" autofocus="" autocapitalize="none" autocomplete="name" class="textinput textInput form-control <?= (isset($errors["name"])) ? "is-invalid" : "" ?>" required="" id="id_name" type="text">
            <?php if (isset($errors["name"])) { ?>
                <p id="error_id_name" class="invalid-feedback"><strong><?= $errors["name"] ?></strong></p>
            <?php } ?>
          </div>
        </div>
        <div id="div_id_subject" class="form-group">
          <label for="id_subject" class=" requiredField">
            Subject<span class="asteriskField">*</span>
          </label>
          <div class="">
            <select name="subject" class="select form-control" required="" id="id_subject">
              <option value="" selected="">---------</option>
              <?php foreach ($subjects as $subject) { ?>
                <option value="<?= $subject->getID() ?>"><?= $subject->name ?></option>
              <?php } ?>
            </select>
            <?php if (isset($errors["subject"])) { ?>
                <p class="text text-danger"><strong><?= $errors["subject"] ?></strong></p>
            <?php } ?>
          </div>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="?url=<?= $GLOBALS['router']->url('quiz_change_list') ?>" class="btn btn-outline-secondary" role="button">Nevermind</a>
      </form>
    </div>
  </div>

<?php

$title = "Tamnza";

$content = ob_get_clean();

require(BASE_DIR . '/views/base.php');

?>