<?php
$helper = Helper::getInstance();

$delay = $helper->setting('UPLOAD_REDIRECT_DELAY');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once ROOT . '/head.php'; ?>
  <?php if ($delay !== FALSE): ?>
    <meta http-equiv="refresh" content="<?= $delay ?>;url=<?= $url ?>">
  <?php endif; ?>
  <title>Upload Failed!</title>
</head>
<body>
<section class="section">
  <div class="container">
    <h1 class="is-danger title is-1 has-text-centered">
      Upload Failed!
    </h1>
    <h2 class="is-danger title is-3 has-text-centered">
      <?= $message ?>
    </h2>
  </div>
</section>
</body>
</html>