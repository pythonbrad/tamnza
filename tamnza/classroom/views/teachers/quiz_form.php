        <div id="div_id_name" class="form-group">
          <label for="id_name" class=" requiredField">
            Name<span class="asteriskField">*</span>
          </label>
          <div class="">
            <input name="name" autofocus="" autocapitalize="none" autocomplete="name" class="textinput textInput form-control <?= (isset($errors["name"])) ? "is-invalid" : "" ?>" required="" id="id_name" type="text" value="<?= isset($quiz) ? $quiz->name : "" ?>">
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
                <option value="<?= $subject->getID() ?>" <?= (isset($quiz) && $quiz->subject->getID() == $subject->getID()) ? 'selected' : '' ?>><?= $subject->name ?></option>
              <?php } ?>
            </select>
            <?php if (isset($errors["subject"])) { ?>
                <p class="text text-danger"><strong><?= $errors["subject"] ?></strong></p>
            <?php } ?>
          </div>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="<?= $GLOBALS['router']->url('quiz_change_list') ?>" class="btn btn-outline-secondary" role="button">Nevermind</a>