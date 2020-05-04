<?php
require_once '../resources/includes/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once '../head.php'; ?>
  <?php require_once ROOT . '/resources/includes/sort.php'; ?>
  <meta name="msapplication-TileImage" content="/favicon/note/ms-icon-144x144.png">
  <?php if (isset($_GET['id'])):
    require '../embed.php';
    getEmbed (NOTE, $_GET['id']);
  else: ?> 
    <!-- Start OEmbed --> 
    <meta content="<?= SITECAMEL ?>" property="og:site_name">
    <meta content="<?= ucfirst(NOTES) ?>" property="og:title">
    <meta content="Get your waifu targets here" property="og:description">
    <meta content="#ebf4f9" name="theme-color">
    <meta content="/resources/note.png" property="og:image">
    <!-- End OEmbed -->
  <?php endif; ?>
</head>
<body>
<?php include_once ROOT . '/resources/includes/menu.php'; ?>
<section class="section">
  <div class="container">
    <?php include_once ROOT . '/resources/includes/redirectAlert.php'; ?>
    <?php include_once ROOT . '/resources/includes/tabs.php'; ?>
    <?php include_once ROOT . '/resources/includes/filter.php'; ?>
    <!-- Start Items -->
<?php
require_once ROOT . '/resources/includes/fetchFilters.php';
?>
    <!-- End Items -->
  </div>
</section>
<?php include_once ROOT . '/resources/includes/scripts.php'; ?>
<?php $helper->scriptFetcher(); ?>
</body>
</html>