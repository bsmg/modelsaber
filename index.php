<?php
require_once 'resources/includes/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!--  <meta content="text/html; charset=utf-8" http-equiv="content-type">
   Start Favicon
  <link rel="apple-touch-icon" sizes="57x57" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192"  href="https://cdn.assistant.moe/favicon/modelsaber/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="https://cdn.assistant.moe/favicon/modelsaber/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="https://cdn.assistant.moe/favicon/modelsaber/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="https://cdn.assistant.moe/favicon/modelsaber/favicon-16x16.png">
  <link rel="manifest" href="https://cdn.assistant.moe/favicon/modelsaber/manifest.json">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="https://cdn.assistant.moe/favicon/modelsaber/favicon/ms-icon-144x144.png">
   End Favicon 
  <title>ModelSaber</title>
  <link href="https://cdn.assistant.moe/css/bulma.css" media="screen" rel="stylesheet">
  <link href="https://cdn.assistant.moe/css/custom.css" rel="stylesheet">
  <link href="https://cdn.assistant.moe/css/light.css" id="light-theme" rel="stylesheet">
  <link href="https://cdn.assistant.moe/css/dark.css" id="dark-theme" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.assistant.moe/js/theme.js"></script>-->
  <?php require_once ROOT . '/head.php'; ?>
  <!-- Start OEmbed -->
  <meta content="ModelSaber" property="og:site_name">
  <meta content="ModelSaber" property="og:title">
  <meta content="Come get your models" property="og:description">
  <meta content="#ebf4f9" name="theme-color">
  <meta content="/resources/modelsaber.png" property="og:image">
  <!-- End OEmbed -->
</head>
<body>
<?php include_once ROOT . '/resources/includes/menu.php'; ?>
<section class="section">
  <div class="container">
    <?php include_once ROOT . '/resources/includes/redirectAlert.php'; ?>
    <?php include_once ROOT . '/resources/includes/tabs.php'; ?>
    <div class="content">
      <h3 class="title is-3 has-text-centered" style='margin-left:0;'>Welcome to <?= SITECAMEL ?>!</h3>
      <p><?= SITECAMEL ?> is a repository for <?= ucfirst(AVATARS) ?>, <?= ucfirst(SABERS) ?>, <?= ucfirst(PLATFORMS) ?>, and <?= ucfirst(NOTES) ?>.</p>
      <p>If you have <a href="<?= MODINSTALLER['link'] ?>"><?= MODINSTALLER['name'] ?></a> installed, you can simply click on the <kbd>Install</kbd> buttons to automatically install these models.</p>
      <p>If you don't, you can still <kbd>Download</kbd> them manually.</p>
      <p>Now with a <strong><em>dark secret...</em></strong></p>
      <p>If you want to learn to make your own, visit the guides for <a href="https://bs.assistant.moe/Avatars"><?= ucfirst(AVATARS) ?></a>, <a href="https://bs.assistant.moe/Sabers"><?= ucfirst(SABERS) ?></a>, <a href="https://bs.assistant.moe/Platforms"><?= ucfirst(PLATFORMS) ?></a>, and <a href="https://bs.assistant.moe/Bloqs"><?= ucfirst(NOTES) ?></a>.</p>
      <p>You can also join the <a href="https://discord.gg/beatsabermods">BSMG Discord</a> if you have any further questions.</p>
    </div>
  </div>
</section>
<?php include_once ROOT . '/resources/includes/scripts.php'; ?>
</body>
</html>
