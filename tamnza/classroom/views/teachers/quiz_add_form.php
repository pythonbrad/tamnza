<?php ob_start() ?>

  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= $GLOBALS['router']->url('quiz_change_list') ?>">My Quizzes</a></li>
      <li class="breadcrumb-item active" aria-current="page">Add a new quiz</li>
    </ol>
  </nav>
  <h2 class="mb-3">Add a new quiz</h2>
  <div class="row">
    <div class="col-md-6 col-sm-8 col-12">
      <form method="post" novalidate>
        <?php require('quiz_form.php') ?>
      </form>
    </div>
  </div>

<?php

$title = "Tamnza";

$content = ob_get_clean();

require(BASE_DIR . '/views/base.php');

?>