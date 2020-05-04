<?php
require_once '../resources/includes/constants.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once ROOT . '/discordOAuth.php';
require_once ROOT . '/model.php';
require '../webhook.php';

/*if (session('access_token')) {
    $user = new User();
    if (!$user->exists(session('discord_user')->id)) {
      unset($user);
    } else {
      $user->read(session('discord_user')->id);
    }
  }*/
if (!$currentUser->isVerified() || !$currentUser->isAdmin()) {
  header('HTTP/1.0 403 Forbidden');
  HTTPError(403);
}

$getId = "";
if (isset($_GET['id'])) {
  $getId = $_GET['id'];
}
$modelType = 'all';
if (isset($_POST['modelType'])) {
  $modelType = $_POST['modelType'];
}
if (!isset($status) && isset($_POST['status'])) {
  $status = $_POST['status'];
  setcookie('status', $status, time()+60*60*24*7, WEBROOT);
  FishyUtils::getInstance()->log("Getting status from status");
  
} else if (isset($_COOKIE['status'])) {
  $status = $_COOKIE['status'];
  FishyUtils::getInstance()->log("Getting status from cookie");
} else if (empty($type)) {
    $status = UNAPPROVED;
    FishyUtils::getInstance()->log("Getting status from empty");
  }
$mode = "print";
if (isset($_POST['mode'])) {
  $mode = $_POST['mode'];
}



//$model = new Model();

/*if(isset($_POST['mode'])){
  $mode = $_POST['mode'];
  $fileID = $_POST['id'];
  $fileType = $_POST['type'];

  function delTree($dir) {
    $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
      (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
  } 

  switch ($mode) {
    case "accept":
      edit(dbConnection::getInstance()->getDB(), 'true', 'yes');
      break;
    case "delete":
      delTree ('../files/'.$fileType.'/'.$fileID);
      pg_query(dbConnection::getInstance()->getDB(), "DELETE FROM models WHERE id = $fileID");
      break;
    case "unapprove":
      edit(dbConnection::getInstance()->getDB(), 'false');
      break;
    case "edit":
      edit(dbConnection::getInstance()->getDB(), 'true');
      break;
  }
  header("Location: " . WEBROOT . "/Manage");
}*/

$modelMode = "";
if (isset($page)) {
  $current_page = $page;
} else {
  $current_page = 1;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!--  <meta content="text/html; charset=utf-8" http-equiv="content-type">
      <title><?= SITECAMEL ?></title>
      <link href="https://cdn.assistant.moe/css/bulma.css" media="screen" rel="stylesheet">
      <link href="https://cdn.assistant.moe/css/custom.css" rel="stylesheet">
      <link href="https://cdn.assistant.moe/css/light.css" id="light-theme" rel="stylesheet">
      <link href="https://cdn.assistant.moe/css/dark.css" id="dark-theme" rel="stylesheet">
      <link href="<?= WEBROOT ?>/resources/input.css" rel="stylesheet">
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <script src="https://cdn.assistant.moe/js/theme.js"></script>-->
    <?php require_once ROOT . '/head.php'; ?>
    <?php require_once ROOT . '/resources/includes/sort.php'; ?>
  </head>
  <body>
    <?php include_once ROOT . '/resources/includes/menu.php'; ?>
    <section class="section">
      <div class="container">
        <?php include_once ROOT . '/resources/includes/adminTabs.php'; ?>
        <?php if (empty($getId)) {
          include_once ROOT . '/resources/includes/filter.php';
        } ?>
        <?php if (empty($getId)): ?>
          <form id="shownFields" action="<?= WEBROOT ?>/forms/shownFields.php" method="post" class="is-toggleable">
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
          <?php if (empty($getId)) {
            $modelMode = 'Manage';
          } ?>
          <?php if (empty($getId)): ?>
            <h3 class="is-3 title">
              <?php
              if ($modelType !== 'all') {
                echo getSuperType($modelType);
              } else {
                echo ucfirst($modelType);
              }
              ?>
            </h3>
            <div class="items-admin items" data-page="<?= $current_page ?>">
          <?php endif; ?>
            <?php $helper->scriptFetcher(); ?>
          <?php if (isset($_GET['page']) && !empty($_GET['page'])): ?>
            <script>changePage(<?= $_GET['page'] ?>);</script>
          <?php endif; ?>
          <?php
          if (!isset($_GET['page'])) {
//  $model = new Model();
//  $output = $model->readMultiple($mode, $modelMode);
//  require ROOT . '/resources/includes/modelAdminCard.php';
            require_once ROOT . '/resources/includes/fetchFilters.php';
          }
          ?>
        <?php if (empty($getId)): ?>
          </div>
<?php endif; ?>
  <?php /* if (!empty($getId)): ?>
    <div class="items-admin">
    <?php endif; */ ?>
      </div>
    </div>
  </section>
    <?php
    $webhookMessage = session('webhookMessage');
    if (!empty($webhookMessage)): ?>
    <script>alert('<?= $webhookMessage ?>');</script>
    <?php unset($_SESSION['webhookMessage']); ?>
    <?php endif; ?>
<?php include_once ROOT . '/resources/includes/scripts.php'; ?>
</body>
</html>