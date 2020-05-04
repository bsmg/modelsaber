<?php
require_once '../resources/includes/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
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
      <h3 class="title is-3 has-text-centered">Welcome to <?= SITECAMEL ?>!</h3>
      <p><?= SITECAMEL ?> is a repository for <?= ucfirst(AVATARS) ?>, <?= ucfirst(SABERS) ?>, <?= ucfirst(PLATFORMS) ?>, and <?= ucfirst(NOTES) ?>.</p>
      <p>If you have the <a href="https://github.com/Assistant/ModAssistant">Mod Assistant</a> installed, you can simply click on the <kbd>Install</kbd> buttons to automatically install these models.</p>
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