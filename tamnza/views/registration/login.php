<?php ob_start(); ?>

    <?php if (isset($errors['login'])) { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <p><?= $errors['login'] ?></p>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
    <?php } ?>
      <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-8 col-12">
          <h2>Log in</h2>
          <form method="post" novalidate>
            <!-- csrf_token -->
            <div id="div_id_username" class="form-group">
            <label for="id_username" class=" requiredField">
                Username<span class="asteriskField">*</span>
            </label>
                <div class="">
                    <input name="username" autofocus="" autocapitalize="none" autocomplete="username" class="textinput textInput form-control <?= (isset($errors["username"])) ? "is-invalid" : "" ?>" required="" id="id_username" type="text">
                    <?php if (isset($errors["username"])) { ?>
                        <p id="error_id_username" class="invalid-feedback"><strong><?= $errors["username"] ?></strong></p>
                    <?php } ?>
                </div>
            </div>
            <div id="div_id_password" class="form-group">
                <label for="id_password" class=" requiredField">
                    Password<span class="asteriskField">*</span>
                </label>
                <div class="">
                    <input name="password" autocomplete="current-password" class="textinput textInput form-control <?= (isset($errors["password"])) ? "is-invalid" : "" ?>" required="" id="id_password" type="password">
                    <?php if (isset($errors["password"])) { ?>
                        <p id="error_id_password" class="invalid-feedback"><strong><?= $errors["password"] ?></strong></p>
                    <?php } ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Log in</button>
          </form>
        </div>
      </div>

<?php

$title = "Tamnza";

$content = ob_get_clean();

require(BASE_DIR . 'views/base.php');

?>
