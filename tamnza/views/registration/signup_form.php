<?php ob_start(); ?>

    <div class="row">
        <div class="col-md-8 col-sm-10 col-12">
            <h2>Sign up as a <?= $user_type ?></h2>
            <form method="post" novalidate>
                <div id="div_id_username" class="form-group">
                    <label for="id_username" class=" requiredField">
                        Username<span class="asteriskField">*</span>
                    </label>
                    <div class="">
                        <input type="text" name="username" maxlength="150" autocapitalize="none" autocomplete="username" autofocus="" class="textinput textInput form-control <?= isset($errors['username']) ? "is-invalid" : "" ?>" required="" id="id_username">
                        <?php if (isset($errors['username'])) { ?>
                            <p id="error_1_id_username" class="invalid-feedback">
                                <strong><?= $errors['username'] ?></strong>
                            </p>
                        <?php } ?>
                        <small id="hint_id_username" class="form-text text-muted">Required. 150 characters or fewer. Letters, digits and @/./+/-/_ only.</small>
                    </div>
                </div>
                <div id="div_id_password1" class="form-group">
                    <label for="id_password1" class=" requiredField">
                        Password<span class="asteriskField">*</span>
                    </label>
                    <div class="">
                        <input type="password" name="password1" autocomplete="new-password" class="textinput textInput form-control <?= isset($errors['password1']) ? "is-invalid" : "" ?>" required="" id="id_password1">
                        <?php if (isset($errors['password1'])) { ?>
                            <p id="error_1_id_password1" class="invalid-feedback">
                                <strong><?= $errors['password1'] ?></strong>
                            </p>
                        <?php } ?>
                    </div>
                </div>
                <div id="div_id_password2" class="form-group">
                    <label for="id_password2" class=" requiredField">
                        Password confirmation<span class="asteriskField">*</span>
                    </label>
                    <div class="">
                        <input type="password" name="password2" autocomplete="new-password" class="textinput textInput form-control <?= isset($errors['password2']) ? "is-invalid" : "" ?>" required="" id="id_password2">
                        <?php if (isset($errors['password2'])) { ?>
                            <p id="error_1_id_password2" class="invalid-feedback">
                                <strong><?= $errors['password2'] ?></strong>
                            </p>
                        <?php } ?>
                        <small id="hint_id_password2" class="form-text text-muted">Enter the same password as before, for verification.</small>
                    </div>
                </div>
                <?php if ($user_type == 'Student') { ?>
                    <div id="div_id_interests" class="form-group">
                        <label class=" requiredField">Interests<span class="asteriskField">*</span></label>
                        <?php if (isset($errors['interests'])) { ?>
                            <p class="text text-danger"><strong><?= $errors['interests'] ?></strong></p>
                        <?php } ?>
                        <div>
                            <?php foreach ($interests as $interest) { ?>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="interests[]" value="<?= $interest->getID() ?>" id="id_interests_0">
                                    <label class="form-check-label" for="id_interests_0"><?= $interest->name ?></label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <button type="submit" class="btn btn-success">Sign up</button>
            </form>
        </div>
  </div>

<?php

$title = 'Tamnza';

$content = ob_get_clean();

require(BASE_DIR . 'views/base.php');

?>