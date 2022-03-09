<?php ob_start() ?>

  <h2 class="mb-3">Update your interests</h2>
  <form method="post" novalidate>
    <div id="div_id_interests" class="form-group">
      <label class=" requiredField">Interests<span class="asteriskField">*</span></label>
      <div>
        <?php foreach ($subjects as $subject) { ?>
          <div class="form-check">
              <input type="checkbox" class="form-check-input" name="interests[]" value="<?= $subject->getID() ?>" id="id_interests_0" <?= in_array($subject->getID(), $interest_ids) ? "checked" : '' ?>>
              <label class="form-check-label" for="id_interests_0"><?= htmlentities($subject->name) ?></label>
          </div>
        <?php } ?>
      </div>
    </div>
    <button type="submit" class="btn btn-success">Save changes</button>
    <a href="<?= $GLOBALS['router']->url('quiz_list') ?>" class="btn btn-outline-secondary">Nevermind</a>
  </form>

<?php

$title = 'Tamnza';

$content = ob_get_clean();

require(BASE_DIR . 'views/base.php');

?>