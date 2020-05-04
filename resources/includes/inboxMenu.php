<?php
if (isset($_POST['notificationId'])) {
  $notification = new notification($currentUser->getDiscordId());
  $notification->readFromId($_POST['notificationId']);
  $notification->dismiss();
  unset($notification);
}
$inbox = new notification($currentUser->getDiscordId());
$notifications = $inbox->read();
?>
<?php if (!findUrl('inbox')): ?>
<?php if ($notifications === FALSE): ?>
  <div id="inbox" class="navbar-item has-dropdown is-hoverable inbox">
    <a href="<?= WEBROOT ?>/Inbox" class="navbar-link">
      <span class="icon is-large">
        <i class="fas fa-bell fa-2x"></i>
      </span>
      <span class="desktop-hidden">Inbox</span>
    </a>
    <div class="navbar-dropdown" style="padding-bottom:0;">
      <a class="navbar-item">
        <span class="no-hover">no unread notifications</span>
      </a>
    </div>
  </div>
<?php else: ?>
  <?php
  $levels = array_map(function($e) {
    return is_object($e) ? $e->getLevel() : $e['level'];
  }, $notifications);
  $level = $inbox->getPriority(array_unique($levels));

  if (count($notifications) >= 10) {
    $count = "9<span style='font-size:.75rem;vertical-align:text-top;line-height:1.25rem;'>+</span>";
    $notificationLimitReached = true;
    $notifications = array_slice($notifications, 0, 9);
  } else {
    $count = count($notifications);
    $notificationLimitReached = false;
  }
  ?>
  <div id="inbox" class="navbar-item has-dropdown is-hoverable inbox mobile-toggle" data-target="inboxForm">
    <a href="<?= WEBROOT ?>/Inbox" class="navbar-link mobile-block-link">
      <span class="icon is-large" style="position: relative;">
        <i class="fas fa-bell fa-2x"></i>
        <div class="has-background-<?= $level ?> no-hover" style="position:absolute;top:0;right:0;width:1.25rem;height:1.25rem;border-radius:100%;text-align:center;">
          <div style="position:relative;width:100%;height:100%;">
            <span class="no-hover" style="position:absolute;bottom:1px;left:0;right:0;width:100%;height:100%;line-height:1.25;"><?= $count; ?></span>
          </div>
          
        </div>
      </span>

      <span class="desktop-hidden">Inbox</span>

    </a>
    <form class="navbar-dropdown" method="post" action="" id="inboxForm" style="padding-bottom:0;">
        <?php foreach ($notifications as $notification): ?>
        <a id="<?= $notification->getId(); ?>" class="navbar-item" style="position:relative;" onclick="this.getElementsByTagName('button')[0].click();">
          <button type="submit" name="notificationId" form="inboxForm" value="<?= $notification->getId(); ?>" formaction="<?= $notification->getLink(); ?>" style="display:none;"></button>
          <span class="is-size-5 has-text-<?= $notification->getLevel(); ?>" style="margin-right: .25rem;">&#9673; </span>
          <span class="no-hover"><?= $notification->getTitle(); ?></span>
          <span class="no-hover" style="position:absolute;top:0;right:.5rem;line-height:1;font-size:.75rem;opacity:.75;"><?= $notification->getAge(); ?></span>
        </a>
      <?php endforeach; ?>
      <?php if ($notificationLimitReached): ?>
      <a href="<?= WEBROOT ?>/Inbox" class="navbar-item mobile-order-top" style="position:relative;">
          <span class="no-hover">see more in the inbox</span>
        </a>
      <?php endif; ?>
    </form>
  </div>
<?php endif; ?>
<?php endif; ?>