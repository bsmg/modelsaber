<?php
HTTPError(403);
require_once '../../resources/includes/constants.php';
require_once ROOT . '/keyHandler.php';
if (!$currentUser->isVerified()) {
  HTTPError(403);
}

// get keys
$keys = KeyHandler::readFromUser($currentUser->getDiscordId());
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once ROOT . '/head.php'; ?>
  
  <meta name="msapplication-TileImage" content="/favicon/note/ms-icon-144x144.png">
  <?php if (isset($_GET['id'])):
    require '../embed.php';
    getEmbed (NOTE, $_GET['id']);
  else: ?> 
    <!-- Start OEmbed --> 
    <meta content="<?= SITECAMEL ?>" property="og:site_name">
    <meta content="API Keys" property="og:title">
    <meta content="API Keys" property="og:description">
    <meta content="#ebf4f9" name="theme-color">
    <meta content="/resources/note.png" property="og:image">
    <!-- End OEmbed -->
  <?php endif; ?>
</head>
<body>
<?php include_once ROOT . '/resources/includes/menu.php'; ?>
<section class="section">
  <div class="container">
    <?php include_once ROOT . '/resources/includes/redirectAlert.php'; ?>
    <?php include_once ROOT . '/resources/includes/tabs.php'; ?>
    <h3 class="title">API Keys<?php info(ROOT . '/resources/components/apiKeyInfo.php', 'small', true) ?></h3>
    <p class="subtitle"><?= KeyHandler::getKeyCount($currentUser->getDiscordId()) ?>/<?= $helper->setting('MAX_API_KEYS') ?> API keys used</p>
    
    <hr>
    
    <form action="<?= WEBROOT ?>/forms/apiKeyCreateForm.php" method="post" id="apiKeyForm">
      <input type="hidden" name="redirect" value="<?= $_SERVER['PHP_SELF'] ?>">
      <div id="apiKeyHeader">
        <div>
          <span>Permissions:</span>
          <?php foreach (KeyHandler::getAllPermissions() as $perm): ?>
          <div class="input" onclick="this.childNodes[1].click();" title="<?= $perm[1] ?>">
              <input type="checkbox" name="can<?= $perm[0] ?>" id="newCan<?= $perm[0] ?>" class="fishy-checkbox">
              <label for="newCan<?= $perm[0] ?>"></label>
              <span><?= $perm[0] ?></span>
            </div>
          <?php endforeach; ?>
        </div>
          
        <button type="submit" class="button">Create key</button>
      </div>
    </form>
    
    <div id="apiKeys" class="items">
      <?php if (empty($keys)): ?>
        <span class="is-size-4">You haven't created any API keys yet...</span>
      <?php else: ?>
        <?php foreach ($keys as $index => $key): ?>
          <form class="item" action="" method="post">
            <input type="hidden" name="redirect" value="<?= $_SERVER['PHP_SELF'] ?>">
            <input type="hidden" name="id" value="<?= $key->getKey() ?>">
            
            <div class="card">
              <div class="card-header">
                <span onclick="copyToClipboard(this)" title="<?= CLIPBOARD ?>"><?= $key->getKey() ?></span>
              </div>
              
              <div class="card-content" style="opacity: 1;">
                <table>
                  <tr>
                    <th>Permission</th>
                    <th>Value</th>
                  </tr>
                  <?php foreach ($key->getPermissions() as $permName => $perm): ?>
                  <?php $keyId = $index . 'can' . $permName; ?>
                    <tr>
                      <td title="<?= $perm[1] ?>"><?= $permName ?></td>
                      <td>
                        <input type="checkbox" name="can<?= $permName ?>" id="<?= $keyId ?>" class="fishy-checkbox" <?= $helper->echoChecked($perm[0]) ?>>
                        <label for="<?= $keyId ?>"></label>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </table>
              </div>
              
              <div class="card-footer">
                <button type="submit" class="card-footer-item" formaction="<?= WEBROOT ?>/forms/apiKeyUpdateForm.php">Update</button>
                <button type="submit" class="card-footer-item" name="type" value="regenerate" formaction="<?= WEBROOT ?>/forms/apiKeyUpdateForm.php">Regenerate</button>
                <button type="submit" class="card-footer-item" formaction="<?= WEBROOT ?>/forms/apiKeyDeleteForm.php">Delete</button>
              </div>
              
            </div>
          </form>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    
  </div>
</section>
<?php require_once ROOT . '/resources/includes/scripts.php'; ?>
</body>
</html>