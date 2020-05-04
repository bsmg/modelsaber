<?php
require_once '../../resources/includes/constants.php';

require_once ROOT . '/discordOAuth.php';
require_once ROOT . '/user.php';

if (!$currentUser->isVerified() || !$currentUser->isAdmin()) {
  header('HTTP/1.0 403 Forbidden');
  HTTPError(403);
}

$getUser = "";
if (isset($_GET['id'])) {
  $getUser = $_GET['id'];
}

$user = new User();
if (!empty($getUser)) {
  $user->read($getUser);
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once ROOT . '/head.php'; ?>
  </head>
  <body>
    <?php include_once ROOT . '/resources/includes/menu.php'; ?>
    <section class="section">
      <div class="container">
        <?php if (empty($getId) && empty($getUser)): ?>
        <?php
        /*
         * TODO
         * 
         * Make this have a few filters like serching verified users or user that
         * can't comment and such.
         * 
         * Should also be able to search for user by discord username and bsaber username.
         */
        ?>
          <form id="shownFields" action="<?= WEBROOT ?>/forms/shownFields.php" method="post" class="is-toggleable" style="display:none;">
            <span class="subtitle is-5">Shown Fields</span>
            <div class="input field is-paddingless no-hover is-relative">
              <div class="control">
                <label class="checkbox">
                  <input type="checkbox" form="shownFields" name="showName" <?= adminCookie('showName'); ?>>
                  Name
                </label>
                <label class="checkbox">
                  <input type="checkbox" form="shownFields" name="showAuthor" <?= adminCookie('showAuthor'); ?>>
                  Author
                </label>
                <label class="checkbox">
                  <input type="checkbox" form="shownFields" name="showTags" <?= adminCookie('showTags'); ?>>
                  Tags
                </label>
                <label class="checkbox">
                  <input type="checkbox" form="shownFields" name="showHash" <?= adminCookie('showHash'); ?>>
                  Hash
                </label>
                <label class="checkbox">
                  <input type="checkbox" form="shownFields" name="showStatus" <?= adminCookie('showStatus'); ?>>
                  Status
                </label>
                <label class="checkbox">
                  <input type="checkbox" form="shownFields" name="showDescription" <?= adminCookie('showDescription'); ?>>
                  Description
                </label>
              </div>
              <div class="control">
                <button class="button" form="shownFields" type="submit">Save</button>
              </div>
            </div>
          </form>
        <?php endif; ?>
          <?php if (empty($getUser)): ?>
            <h3 class="is-3 title">Users</h3>
            <div class="items-admin items">
          <?php endif; ?>
          <?php
          if (empty($getUser)) {
            $output = $user->readAll();
            $oldUser = $user;
            foreach ($output as $user) {
              include ROOT . '/resources/templates/printAdminUser.php';
            }
          } else if ($user->isLoggedIn()) {
            include_once ROOT . '/resources/templates/printAdminSingleUser.php';
          } else {
            echo "<div style='width:100%;text-align:center;'><span class='is-size-4'>That user wasn't found</span></div>";
          }
          ?>
          <?php if (empty($getUser)): ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>
    <?php include_once ROOT . '/resources/includes/scripts.php'; ?>
  </body>
</html>