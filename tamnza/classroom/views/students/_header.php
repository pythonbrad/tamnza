<h2>Quizzes</h2>
<p class="text-muted">
  Subjects:
  <?php foreach ($user->student->interests as $interest) { ?>
        <?= $interest->subject->getHtmlBadge() ?>
  <?php } ?>
  <a href="<?= $GLOBALS['router']->url('student_interests') ?>"><small>(update interests)</small></a>
</p>

<ul class="nav nav-tabs mb-3">
  <li class="nav-item">
    <a class="nav-link <?= ($active == 'new') ? 'active' : '' ?>" href="<?= $GLOBALS['router']->url('quiz_list') ?>">New</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= ($active == 'taken') ? 'active' : '' ?>" href="<?= $GLOBALS['router']->url('taken_quiz_list') ?>">Taken</a>
  </li>
</ul>