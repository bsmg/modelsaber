<?php
require_once '../resources/includes/constants.php';
require_once ROOT . '/user.php';
  $profileUser = new User();
  $profileUser->read($_GET['user']);
  
  $nameImage = $profileUser->getIcon();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once '../head.php'; ?>
  </head>
  <body>
    <div style="position: relative;display: flex;">
      <div class="card" style="width:calc(100%/4 - (3/4*1em));">
        <div class="card-image">
          <figure class="image is-1by1 reset-cursor">
            <img src="<?= $profileUser->getAvatar(); ?>?size=1024" alt="Profile image">
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
                <img src="<?= $profileUser->getAvatar(); ?>?size=128" alt="Discord Image" class="is-rounded">
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
        <div class="card-footer">
          <a class="card-footer-item" href="<?= $profileUser->getBsaber(); ?>" target="_blank" style="border-bottom-left-radius:6px;">BeastSaber Profile</a>
        </div>
      </div>
    </div>
  </body>
</html>
