<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $title ?></title>
    <link rel="icon" href="<?= STATIC_DIR ?>img/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Clicker+Script" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?= STATIC_DIR ?>vendor/fontello-2f186091/css/fontello.css">
    <link rel="stylesheet" type="text/css" href="<?= STATIC_DIR ?>css/app.css">
    <?php if (isset($_SESSION['is_authenticated']) && $_SESSION['is_teacher']) { ?>
      <link rel="stylesheet" type="text/css" href="<?= STATIC_DIR ?>css/teachers.css">
    <?php } else { ?>
      <link rel="stylesheet" type="text/css" href="<?= STATIC_DIR ?>css/students.css">
    <?php } ?>
  </head>
  <body>
    <div class="container my-4">
      <div class="row justify-content-center">
        <div class="col-md-10 col-sm-12">
          <div class="row">
            <div class="col-6">
              <h1 class="logo">
                <a href="?url=/">
                  Tamnza
                <?php if (isset($_SESSION['is_authenticated'])) { ?>
                    <?php if ($_SESSION['is_teacher']) { ?>
                      <span class="icon-feather" data-toggle="tooltip" data-placement="right" title="Teacher profile"></span>
                    <?php } else { ?>
                      <span class="icon-graduation-cap" data-toggle="tooltip" data-placement="right" title="Student profile"></span>
                    <?php } ?>
                <?php } ?>
                </a>
              </h1>
            </div>
            <div class="col-6 text-right">
            <?php if (isset($_SESSION['is_authenticated'])) { ?>
                <p class="pt-3">Logged in as <strong><?= $_SESSION['username'] ?></strong>. <a href="?url=/logout">Log out</a>.</p>
            <?php } else { ?>
                <a href="?url=/login" class="btn btn-light" role="button">Log in</a>
                <a href="?url=/signup" class="btn btn-primary" role="button">Sign up</a>
            <?php } ?>
            </div>
          </div>
          <div class="card mb-3">
            <div class="card-body">
            <?php foreach ($_SESSION['messages'] ?? array() as $tag => $message) { ?>
                <div class="alert alert-<?= $tag ?> alert-dismissible fade show" role="alert">
                <?= $message ?>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
            <?php } ?>

            <?= $content ?>
            </div>
          </div>
          <footer>
            <a href="https://resulam.com">© Resulam.com</a>
            /
            <a href="https://resulam.com">© Resulam.com</a>
            /
            <a href="https://resulam.com">© Resulam.com</a>
          </footer>
        </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script type="text/javascript">
      $(function () {
        $('[data-toggle="tooltip"]').tooltip();
      })
    </script>
  </body>
</html>
<?php
// We clean the session messages
$_SESSION['messages'] = array();
?>
