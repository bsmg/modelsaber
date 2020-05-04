<?php
require_once '../../resources/includes/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!--  <meta content="text/html; charset=utf-8" http-equiv="content-type">
   Start Favicon
  <link rel="apple-touch-icon" sizes="57x57" href="https://cdn.assistant.moe/favicon/modelsaber/saber/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="https://cdn.assistant.moe/favicon/modelsaber/saber/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="https://cdn.assistant.moe/favicon/modelsaber/saber/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="https://cdn.assistant.moe/favicon/modelsaber/saber/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="https://cdn.assistant.moe/favicon/modelsaber/saber/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="https://cdn.assistant.moe/favicon/modelsaber/saber/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="https://cdn.assistant.moe/favicon/modelsaber/saber/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="https://cdn.assistant.moe/favicon/modelsaber/saber/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="https://cdn.assistant.moe/favicon/modelsaber/saber/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192"  href="https://cdn.assistant.moe/favicon/modelsaber/saber/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="https://cdn.assistant.moe/favicon/modelsaber/saber/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="https://cdn.assistant.moe/favicon/modelsaber/saber/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="https://cdn.assistant.moe/favicon/modelsaber/saber/favicon-16x16.png">
  <link rel="manifest" href="https://cdn.assistant.moe/favicon/modelsaber/saber/manifest.json">
  <meta name="msapplication-TileColor" content="#ffffff">
  
   End Favicon 
  <title>ModelSaber</title>
  <link href="https://cdn.assistant.moe/css/bulma.css" media="screen" rel="stylesheet">
  <link href="https://cdn.assistant.moe/css/custom.css" rel="stylesheet">
  <link href="https://cdn.assistant.moe/css/light.css" id="light-theme" rel="stylesheet">
  <link href="https://cdn.assistant.moe/css/dark.css" id="dark-theme" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <script src="https://cdn.assistant.moe/js/theme.js"></script>-->
  <?php require_once '../../head.php'; ?>
  <?php require_once ROOT . '/resources/includes/sort.php'; ?>
  <meta name="msapplication-TileImage" content="/favicon/saber/ms-icon-144x144.png">
  <?php if (isset($_GET['id'])):
    require '../embed.php';
    getEmbed (SABER, $_GET['id']);
  else: ?>
    <!-- Start OEmbed --> 
    <meta content="<?= SITECAMEL ?>" property="og:site_name">
    <meta content="<?= ucfirst(SABERS) ?>" property="og:title">
    <meta content="Waifu whackers" property="og:description">
    <meta content="#ebf4f9" name="theme-color">
    <meta content="/resources/saber.png" property="og:image">
    <!-- End OEmbed -->
  <?php endif; ?>
</head>
<body>
<?php include_once ROOT . '/resources/includes/menu.php'; ?>
<section class="section">
  <div class="container">
    <?php include_once ROOT . '/resources/includes/redirectAlert.php'; ?>
    <?php include_once ROOT . '/resources/includes/questTabs.php'; ?>
    <?php include_once ROOT . '/resources/includes/filter.php'; ?>
    <!-- Start Items -->
<?php
//require_once '../cards.php';
//if (isset($_GET['id'])) {
//  $id = $_GET['id'];
//} else {
//  $id = "";
//}
//printCards("saber", $id, $sort, $sort_dir, $limit, '', '*');
require_once ROOT . '/model.php';
$model = new Model();
$output = $model->readMultiple('print', 'quest');
require_once ROOT . '/resources/includes/modelCard.php';
//require_once '../../cards.php';
require_once ROOT . '/resources/includes/fetchFilters.php';
?>
    <!-- End Items -->
  </div>
</section>
<?php include_once ROOT . '/resources/includes/scripts.php'; ?>
<?php $helper->scriptFetcher(); ?>
</body>
</html>