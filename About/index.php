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
    <!--<div class="content">-->
      <h3 class="title is-3 has-text-centered">About <?= SITECAMEL ?></h3>
      <?php if (!empty($aboutContributers)): ?>
      <h4 class="title is-3 is-marginless">Contributors</h4>
      <span class="subtitle">These are the people that you should be thanking for making <?= SITECAMEL ?> what it is today.</span>
      <div id="contributors" class="items">
      <?php foreach ($aboutContributers as $contributer): ?>
        <div class="item">
      <div class="card contributor">
        <div class="card-image is-1by1">
          <figure class="image is-1by1">
            <img src="../resources/contributors/<?= $contributer['image']; ?>">
          </figure>
        </div>
        <div class="card-content" style="opacity: 1;">
          <div class="media-content">
            <p class="title is-4 is-singleLine"><?= $contributer['name']; ?></p>
            <p class="subtitle is-6 is-singleLine"><span class="icon">
        <i class="fab fa-discord" style="font-size: 16px; width: auto;color: #7289DA;"></i>
      </span><?= (empty($contributer['discord']))? 'None' : $contributer['discord']; ?></p>
          </div>
          <div class="content">
            <span class="description"><?= (empty($contributer['description']))? 'None' : $contributer['description']; ?></span>
            <hr style="margin-bottom: .5em;">
            <span class="subtitle is-5">Contributions</span>
            
            <ul>
            <?php foreach ($contributer['contributions'] as $contribution): ?>
              <li class="is-singeLine"><?= $contribution ?></li>
            <?php endforeach; ?>
            </ul>
          </div>
        </div>
        <div class="card-footer">
          <span class="card-footer-item no-hover"><?= $contributer['title']; ?></span>
        </div>
      </div>
        </div>
      <?php endforeach; ?>
      </div>
      <?php endif; ?>
      <?php if (!empty($copyrights)): ?>
      <h4 class="title is-3 is-marginless">Resources</h4>
      <?php foreach ($copyrights as $copyright): ?>
      <p><a href="<?= $copyright['link']; ?>"><?= $copyright['title']; ?></a> Copyright &copy; <?= $copyright['year']; ?> <a href="<?= $copyright['owner']['website']; ?>"><?= $copyright['owner']['name']; ?></a></p>
      <?php endforeach; ?>
      <?php endif; ?>
    <!--</div>-->
  </div>
</section>
<?php include_once ROOT . '/resources/includes/scripts.php'; ?>
</body>
</html>