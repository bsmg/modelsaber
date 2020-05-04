<?php
require_once '../resources/includes/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once ROOT . '/head.php'; ?>
  <?php require_once ROOT . '/resources/includes/sort.php'; ?>
  <meta name="msapplication-TileImage" content="/favicon/misc/ms-icon-144x144.png">
  <?php if (isset($_GET['id'])):
    require ROOT . '/embed.php';
    getEmbed (MISC, $_GET['id']);
  else: ?>
    <!-- Start OEmbed --> 
    <meta content="<?= SITECAMEL ?>" property="og:site_name">
    <meta content="<?= ucfirst(MISCS) ?>" property="og:title">
    <meta content="Miscelanious things for your waifu" property="og:description">
    <meta content="#ebf4f9" name="theme-color">
    <meta content="/resources/misc.png" property="og:image">
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