    <div id="div_id_text" class="form-group">
      <label for="id_text" class=" requiredField">Question<span class="asteriskField">*</span> </label>
      <div class="">
        <input name="text" autofocus="" autocapitalize="none" autocomplete="text" class="textinput textInput form-control <?= (isset($errors["text"])) ? "is-invalid" : "" ?>" required="" id="id_text" type="text" value="<?= isset($question) ? $question->text : "" ?>">
            <?php if (isset($errors["text"])) { ?>
                <p id="error_id_text" class="invalid-feedback"><strong><?= $errors["text"] ?></strong></p>
            <?php } ?>
      </div>
    </div>