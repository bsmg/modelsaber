<?php
$helper = Helper::getInstance();

global $url;
// global $superType;
global $id;
$delay = $helper->setting('UPLOAD_REDIRECT_DELAY');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once ROOT . '/head.php'; ?>
  <?php if ($delay !== FALSE): ?>
    <meta http-equiv="refresh" content="<?= $delay ?>;url=<?= $url ?>">
  <?php endif; ?>
  <title>Upload Complete!</title>
</head>
<body>
<section class="section">
  <div class="container">
    <h1 class="title is-1 has-text-centered">
      Upload Complete!
    </h1>
    <h2 class="title is-3 has-text-centered">
      Please wait while we manually approve your submission.
      You will be able to see it <a href="<?= $url ?><?= $superType ?>/?id=<?= $id ?>">here</a> once it's approved. (save this)
    </h2>
  </div>
</section>
</body>
</html>