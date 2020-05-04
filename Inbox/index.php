<?php
require_once '../resources/includes/constants.php';

if (isset($_POST['dismissAll'])) {
  $notification = new notification($currentUser->getDiscordId());
  $notification->dismissAll();
  unset($notification);
}
if (isset($_POST['notificationId'])) {
  $notification = new notification($currentUser->getDiscordId());
  $notification->readFromId($_POST['notificationId']);
  $notification->dismiss();
  unset($notification);
}
$inbox = new notification($currentUser->getDiscordId());
if (isset($_GET['level'])) {
  $notifications = $inbox->readFromLevel($_GET['level']);
} else {
  $notifications = $inbox->read();
}

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
        <h2 class="title is-2 has-text-centered has-underline-klouder" style="margin-bottom:1.5rem;">Inbox</h2>
          <?php
          if (isset($_GET['level']) && !empty($_GET['level']) && $_GET['level'] == 'all') {
            $level = $_GET['level'];
          } else {
            $level = 'white';
          }
          ?>
        <form method="post" action="" id="inboxForm" class="inbox-page">
          <div id="inboxHeader" class="field has-addons is-fullwidth">
            <div class="control has-icons-left">
              <input type="submit" class="input" value="Search" formmethod="get">
              <span class="icon is-left">
                <i class="fas fa-search"></i>
              </span>
            </div>
            <div class="control select is-expanded">
              <select class="input no-hover has-text-<?= $level ?>" name="level">
                <option class="has-text-white" value="all">&#9673; All</option>
                <?php foreach (notification::getAllLevels() as $key => $level): ?>
                  <option class="has-text-<?= $key ?>" value="<?= $key ?>" <?= (isset($_GET['level']) && $_GET['level'] == $key)? 'selected': ''; ?>>&#9673; <?= ucwords($level); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="control has-icons-left">
              <input type="submit" class="input" name="dismissAll" value="Dismiss All">
              <span class="icon is-left">
                <i class="fas fa-times"></i>
              </span>
            </div>
          </div>

          <?php foreach ($notifications as $notification): ?>
            <article class="message is-<?= $notification->getLevel(); ?>">
              <div class="message-header">
                <span class="no-hover"><?= $notification->getAge(); ?>: <a href="<?= $notification->getLink(); ?>"><?= $notification->getTitle(); ?></a></span>
                <button class="delete" aria-label="delete" type="submit" name="notificationId" form="inboxForm" value="<?= $notification->getId(); ?>"></button>
              </div>
              <div class="message-body input no-hover">
                <span class="is-size-5 has-text-<?= $notification->getLevel(); ?>"><?= ucwords($notification->getLevel(true)); ?></span><br>
                <span class="no-hover has-text-white"><?= $parsedown->line($notification->getMessage()); ?></span>
                <!--<span class="no-hover" style="position:absolute;top:0;right:.5rem;line-height:1;font-size:.75rem;opacity:.75;"><?= $notification->getAge(); ?></span>-->
              </div>
            </article>
          <?php endforeach; ?>
        </form>
      </div>
    </section>
    <?php include_once ROOT . '/resources/includes/scripts.php'; ?>
  </body>
</html>