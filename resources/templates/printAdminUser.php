<div class="item" data-name="<?= $user->getUsername() ?>" data-id="<?= $user->getDiscordId() ?>">
  <div class="card">
    <a class="card-image is-relative" href="?id=<?= $user->getDiscordId() ?>">
      <figure class="image is-1by1">
        <?php if ($helper->checkRemoteFile($user->getAvatar() . '?size=512')): ?>
          <img src="<?= $user->getAvatar() ?>?size=512" alt="<?= $user->getUsername() ?>">
        <?php else: ?>
          <img src="<?= $user->getDefaultAvatar() ?>" alt="Discord Default">
        <?php endif; ?>
      </figure>
    </a>
    <div class="card-content" style="opacity:1;border-top-left-radius:0;border-top-right-radius:0;">
      <div class="media">
        <div class="media-left">
          <figure class="image is-48x48 reset-cursor">
            <?php if ($helper->checkRemoteFile($user->getAvatar() . '?size=128')): ?>
              <img src="<?= $user->getAvatar() ?>?size=128" alt="Discord Image" class="is-rounded">
            <?php else: ?>
              <img src="<?= $user->getDefaultAvatar() ?>" alt="Discord Default" class="is-rounded">
            <?php endif; ?>
          </figure>
        </div>
        <div class="media-content">
          <span class="title is-4 is-spaced">
            <span><?= ($user->isBobby()) ? $user->bobbyfy($user->getUsername()) : $user->getUsername() ?></span>
          </span>
          <p class="subtitle is-6"><span class="icon">
              <i class="fab fa-discord" style="font-size: 16px; width: auto;color: #7289DA;"></i>
            </span><?= $user->getUsername() . '#' . $user->getDiscriminator() ?></p>
        </div>
      </div>
    </div>

    <div class="card-footer">
      <a href="?id=<?= $user->getDiscordId() ?>" class="card-footer-item" style="border-bottom-left-radius:6px;border-bottom-right-radius:6px;">Details</a>
    </div>
  </div>
</div>