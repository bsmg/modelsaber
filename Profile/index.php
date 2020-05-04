<?php
require_once '../resources/includes/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once ROOT . '/head.php'; ?>
  <?php require_once ROOT . '/resources/includes/sort.php'; ?>
  
  <meta name="msapplication-TileImage" content="/favicon/note/ms-icon-144x144.png">
  <?php
  require_once ROOT . '/model.php';
  require_once ROOT . '/user.php';
  $profileUser = new User();
  $profileUser->read($_GET['user']);
  $model = new Model();
  $output = $model->readMultiple('print', 'Profile');
  if (gettype($output) !== 'array') {
    unset($output);
  }
  
  $nameImage = $profileUser->getIcon();
  ?>
  <?php if (isset($_GET['id'])):
    require '../embed.php';
    getEmbed (NOTE, $_GET['id']);
  else: ?> 
    <!-- Start OEmbed --> 
    <meta content="<?= SITECAMEL ?>" property="og:site_name">
    <meta content="<?php  $profileUser->getUsername(); ?>" property="og:title">
    <meta content="Profile page" property="og:description">
    <meta content="#ebf4f9" name="theme-color">
    <meta content="/resources/note.png" property="og:image">
    <!-- End OEmbed -->
  <?php endif; ?>
</head>
<body>
  <form action="<?= WEBROOT ?>/forms/updateUser.php" method="post" class="is-hidden" id="editForm">
    <input type="hidden" name="redirect" value="<?= $_SERVER['PHP_SELF'] ?>">
    <input type="hidden" name="id" value="<?= $profileUser->getDiscordId(); ?>">
  </form>
<?php include_once ROOT . '/resources/includes/menu.php'; ?>
<section class="section">
  <div class="container">
    <?php include_once ROOT . '/resources/includes/redirectAlert.php'; ?>
    <?php include_once ROOT . '/resources/includes/tabs.php'; ?>
    <?php include_once ROOT . '/resources/includes/filter.php'; ?>
    <?php // include_once ROOT . '/resources/includes/profileSection.php'; ?>
    <div id="profile">
      <div class="card">
        <div class="card-image">
          <figure class="image is-1by1 reset-cursor">
            <?php if ($helper->checkRemoteFile($profileUser->getAvatar() . '?size=512')): ?>
              <img src="<?= $profileUser->getAvatar(); ?>?size=512" alt="Profile image">
            <?php else: ?>
              <img src="<?= $profileUser->getDefaultAvatar() ?>" alt="Discord Default">
            <?php endif; ?>
            
            <?php if ($profileUser->isTrusted()): ?>
              <span class="icon is-medium has-text-success" style="position:absolute;top:0;right:0;margin:.75rem;" title="Trusted Author">
                <i class="fas fa-check" style="font-size: 1.5rem;"></i>
              </span>
            <?php endif; ?>
          </figure>
        </div>
        <div class="card-content" style="opacity:1;border-top-left-radius:0;border-top-right-radius:0;">
          <div class="media">
            <div class="media-left">
              <figure class="image is-48x48 reset-cursor">
                <?php if ($helper->checkRemoteFile($profileUser->getAvatar() . '?size=128')): ?>
                  <img src="<?= $profileUser->getAvatar(); ?>?size=128" alt="Discord Image" class="is-rounded">
                <?php else: ?>
                  <img src="<?= $profileUser->getDefaultAvatar() ?>" alt="Discord Default" class="is-rounded">
                <?php endif; ?>
              </figure>
            </div>
            <div class="media-content">
              <span class="title is-4 is-spaced">
                <span><?= ($profileUser->isBobby()) ? $profileUser->bobbyfy($profileUser->getUsername()) : $profileUser->getUsername(); ?></span>
                <?php if ($nameImage !== false): ?>
                  <figure class="image is-16x16 reset-cursor" style="display:inline-block;">
                    <img src="<?= $nameImage['image']; ?>" title="<?= $nameImage['title']; ?>" style="border-radius:0;">
                  </figure>
                <?php endif; ?>
              </span>
              <p class="subtitle is-6"><span class="icon">
                  <i class="fab fa-discord" style="font-size: 16px; width: auto;color: #7289DA;"></i>
                </span><?= $profileUser->getUsername() . '#' . $profileUser->getDiscriminator(); ?></p>
            </div>
          </div>

          <div class="content has-text-white">
            <hr>
            <?= ($profileUser->isBobby()) ? $parsedown->line($profileUser->bobbyfy($profileUser->getDescription())) : $parsedown->line($profileUser->getDescription()); ?>
          </div>
        </div>
        <?php if ($profileUser->getBsaber() !== FALSE): ?>
          <div class="card-footer">
            <a class="card-footer-item" href="<?= $profileUser->getBsaber() ?>" target="_blank" style="border-bottom-left-radius:6px;">BeastSaber Profile</a>
          </div>
        <?php endif; ?>
      </div>
      <div class="profile-buttons">
        <!--<div style="width:calc(100% - 256px - .75rem);">-->
        <?php if (isset($_GET['edit']) && $output[0]->isAuthor($currentUser->getDiscordId())): ?>
          <!--<form action="" method="post" id="descriptionForm">-->
          <div class="field">
            <label>BeastSaber</label>
            <div class="control">
              <input class="input" type="text" name="bsaber" value="<?= $profileUser->getBsaber(true); ?>" form="editForm" placeholder="https://bsaber.com/members/ThisPartOnly">
            </div>
          </div>
          <div class="field">
            <label>Description</label>
            <div class="control">
              <textarea class="content textarea" name="description" form="editForm"><?= $profileUser->getDescription(); ?></textarea>
            </div>
            <p class="help"><?= MARKDOWNSUPPORT; ?></p>
          </div>
          <div class="field is-grouped">
            <div class="control">
              <input type="submit" class="button upload-button" name="action" form="editForm">
            </div>
            <div class="control">
              <a href="?user=<?= $profileUser->getDiscordId(); ?>" class="button upload-button">Cancel</a>
            </div>
          </div>
          <!--</form>-->
        <?php else: ?>
          <div>
            <?php if (isset($output) && $output[0]->isAuthor($currentUser->getDiscordId())): ?>
              <a class="button" href="?user=<?= $profileUser->getDiscordId(); ?>&edit">
                <span>Edit</span>
                <span class="icon">
                  <i class="fas fa-pen"></i>
                </span>
              </a>
            <?php if (false): //disabled while the action api is under construction ?>
              <a class="button" href="Keys/">
                <span>API Keys</span>
                <span class="icon">
                  <i class="fas fa-key"></i>
                </span>
              </a>
            <?php endif; ?>
            <?php endif; ?>
          </div>
        <?php endif; ?>
        <!--</div>-->
      </div>
    </div>
    <hr>
    <!-- Start Items -->
<?php
if (!isset($_GET['edit'])) {
  require_once ROOT . '/resources/includes/fetchFilters.php';
}
?>
    <!-- End Items -->
  </div>
</section>
<?php require_once ROOT . '/resources/includes/scripts.php'; ?>
<?php $helper->scriptFetcher(); ?>
</body>
</html>