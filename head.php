<?php
//require_once __DIR__ . '/resources/includes/constants.php';
$device = array_search(true, DEVICE);
?>
  <meta content="text/html; charset=utf-8" http-equiv="content-type">
  <!-- Start Favicon-->
  <link rel="apple-touch-icon" type="image/png" sizes="192x192" href="<?= WEBROOT ?>/resources/manifest/icon-app-192.png">
  <link rel="apple-touch-icon" type="image/png" sizes="512x512" href="<?= WEBROOT ?>/resources/manifest/icon-app-512.png">
  <link rel="icon" type="image/png" sizes="192x192" href="<?= WEBROOT ?>/resources/manifest/icon-192.png">
  <link rel="icon" type="image/png" sizes="512x512" href="<?= WEBROOT ?>/resources/manifest/icon-512.png">
  <meta name="theme-color" content="#535aa2">
  <link rel="manifest" href="<?= WEBROOT ?>/resources/manifest/manifest.json"<?= ($helper->isDevelopment()) ? ' crossorigin="use-credentials"' : '' ?>>
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="https://cdn.assistant.moe/favicon/modelsaber/favicon/ms-icon-144x144.png">
  <!-- End Favicon -->
  <!-- Extra Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Kaushan+Script&display=swap" rel="stylesheet">
  <!-- !Extra Fonts -->
  <title><?= SITECAMEL ?></title>
  <link href="https://cdn.assistant.moe/css/bulma.css" media="screen" rel="stylesheet">
  <link href="https://cdn.assistant.moe/css/custom.css" rel="stylesheet">
  <link href="https://cdn.assistant.moe/css/light.css" id="light-theme" rel="stylesheet">
  <link href="https://cdn.assistant.moe/css/dark.css" id="dark-theme" rel="stylesheet">
  <link href="<?= WEBROOT ?>/resources/darkV2.css" id="dark-theme-2" rel="stylesheet">
  <link href="<?= WEBROOT ?>/resources/input.css" rel="stylesheet">
  <link href="<?= WEBROOT ?>/resources/fishyUtils.css" rel="stylesheet">
  <?php if ($device == 'quest') { echo '<link href=" ' . WEBROOT . '/resources/quest.css" rel="stylesheet">'; } ?>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.assistant.moe/js/theme.js"></script>
  <script src="<?= WEBROOT ?>/helper.js"></script>
  <!-- Hi mom! -->