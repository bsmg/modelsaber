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
      <h3 class="title is-3 has-text-centered"><?= SITECAMEL ?> Terms of Service</h3>
      <p><?= SITECAMEL ?> is a repository for <?= ucfirst(AVATARS) ?>, <?= ucfirst(SABERS) ?>, <?= ucfirst(PLATFORMS) ?>, and <?= ucfirst(NOTES) ?>.</p>
      <p>If you have the <a href="https://github.com/Assistant/ModAssistant">Mod Assistant</a> installed, you can simply click on the <kbd>Install</kbd> buttons to automatically install these models.</p>
      <p>If you don't, you can still <kbd>Download</kbd> them manually.</p>
      <p>Now with a <strong><em>dark secret...</em></strong></p>
      <p>If you want to learn to make your own, visit the guides for <a href="https://bsmg.wiki/models/custom-avatars.html"><?= ucfirst(AVATARS) ?></a>, <a href="https://bsmg.wiki/models/custom-sabers.html"><?= ucfirst(SABERS) ?></a>, <a href="https://bsmg.wiki/models/custom-platforms.html"><?= ucfirst(PLATFORMS) ?></a>, and <a href="https://bsmg.wiki/models/custom-notes.html"><?= ucfirst(NOTES) ?></a>.</p>
      <p>You can also join the <a href="https://discord.gg/beatsabermods">BSMG Discord</a> if you have any further questions.</p>
      <form id="tosSubmit" action="<?= WEBROOT ?>/forms/tosSubmit.php" method="post">
        <div class="field">
          <div class="control">
            <label>
            <input type="checkbox" name="TOSAccept" placeholder="I have read the Teams of Service" onclick="document.getElementById('tosAgree').disabled = !this.checked;">
            I agree to the terms and conditions
            </label>
          </div>
        </div>
        <div class="field">
          <div class="control">
            <button class="button is-fullwidth" type="submit" name="TOSAction" value="false">Disagree</button>
          </div>
          <div class="control">
            <button id="tosAgree" class="button is-fullwidth" disabled type="submit" name="TOSAction" value="true">Accept terms</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>
<?php include_once ROOT . '/resources/includes/scripts.php'; ?>
</body>
</html>