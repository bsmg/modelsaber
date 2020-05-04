<?php
header('HTTP/1.0 403 Forbidden');
require_once '../resources/includes/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once '../head.php'; ?>
</head>
<body>
<?php include_once ROOT . '/resources/includes/menu.php'; ?>
<section class="section">
  <div class="container">
    <?php include_once ROOT . '/resources/includes/redirectAlert.php'; ?>
    <?php include_once ROOT . '/resources/includes/tabs.php'; ?>
    <div class="content">
      <h1 class="title is-3 has-text-centered"><span class="down">FATAL ERROR</span></h1>
      <!--<h2 class="subtitle has-text-centered has-text-danger"><span class="down">UNAUTHORIZED</span></h2>-->
      <p class="has-text-centered"><?= $_GET['message']; ?></p>
    </div>
  </div>
</section>
<?php include_once ROOT . '/resources/includes/scripts.php'; ?>
</body>
</html>