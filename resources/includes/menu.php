<script>document.body.id = '<?= $device; ?>';</script>
<!-- Start Nav Bar -->
<nav class="navbar has-shadow" aria-label="main navigation">
  <div class="container">
    <div class="navbar-brand">
      <a class="navbar-item modelsaber-logo" href="<?= WEBROOT ?>/?<?= getDevicePlatform() ?>">
        <span class="icon is-large">
          <?php //echo file_get_contents(WEBROOT . '/resources/modelsaber-logo-web.svg') ?>
          <img src="<?= WEBROOT ?>/resources/modelsaber-logo-web.svg">
        </span>
      </a>
      <a role="button" class="navbar-burger" data-target="navMenu" aria-label="menu"  aria-expanded="false">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
      </a>
    </div>
    <div class="navbar-menu" id="navMenu">
      <div class="navbar-start">
        <?php if ($currentUser->isVerified()): ?>
        <a class="navbar-item upload modal-trigger" data-target="upload" href="<?= WEBROOT ?>/Upload" title="Upload">
          <span class="icon is-large">
            <i class="fas fa-cloud-upload-alt fa-2x"></i>
          </span>
          <span class="desktop-hidden">Upload</span>
        </a>
        <?php endif; ?>
        <?php if (true): //turned off untill quest support is implemented ?>
        <a class="navbar-item quest-hidden <?= (getDevicePlatform() == 'pc')? 'is-klouder' : ''; ?>" data-target="pc" href="./?pc" title="PC">
          <span class="icon is-large">
            <i class="fas fa-desktop fa-2x"></i>
          </span>
          <span class="desktop-hidden">PC</span>
        </a>
        <a class="navbar-item quest-hidden <?= (getDevicePlatform() == 'quest')? 'is-klouder' : ''; ?>" data-target="quest" href="./?quest" title="Quest">
          <span class="icon is-large">
            <i class="fas fa-mobile-alt fa-2x"></i>
          </span>
          <span class="desktop-hidden">Quest</span>
        </a>
        <?php endif; ?>
        <?php if ($currentUser->isLoggedIn()): ?>
        <a class="navbar-item profile modal-trigger" href="<?= WEBROOT ?>/Profile?user=<?= $currentUser->getDiscordId(); ?>" title="Profile">
          <figure class="image is-48x48" style="display:inline-flex;align-items:center;justify-content:center;">
            <?php if ($helper->checkRemoteFile($currentUser->getAvatar() . '?size=1024')): ?>
              <img src="<?= $currentUser->getAvatar(); ?>?size=128" style="max-height:none;height:2.5rem;width:2.5rem;display:block;" class="is-rounded" alt="Avatar image">
            <?php else: ?>
              <img src="<?= $currentUser->getDefaultAvatar() ?>" alt="Discord Default">
            <?php endif; ?>
          </figure>
          <span class="desktop-hidden">Profile</span>
        </a>
        <?php if (!findUrl('inbox')): ?>
        <?php include_once ROOT . '/resources/includes/inboxMenu.php'; ?>
        <?php endif; ?>
        <?php endif; ?>
        <?php if($currentUser->isLoggedIn()): ?>
        <a class="navbar-item login-logout modal-trigger" href="?action=logout" title="Log out">
          <span class="icon is-large">
            <i class="fas fa-sign-out-alt fa-2x"></i>
          </span>
          <span class="desktop-hidden">Log Out</span>
        </a>
        <?php else: ?>
        <a class="navbar-item login-logout modal-trigger" href="?action=login" title="Log in">
          <span class="icon is-large">
            <i class="fas fa-sign-in-alt fa-2x"></i>
          </span>
          <span class="desktop-hidden">Log In</span>
        </a>
        <?php endif; ?>
      </div>
      <div class="navbar-end">
        <?php if(array_search(ROOT . '/resources/includes/sort.php', str_replace('\\', '/', get_included_files()), false)) {
          require_once ROOT . '/resources/includes/sortMenu.php';
        }
        ?>
        <a class="navbar-item settings modal-trigger" href="<?= WEBROOT ?>/About" title="About">
          <span class="icon is-large">
            <i class="fas fa-info-circle fa-2x"></i>
          </span>
          <span class="desktop-hidden">About</span>
        </a>
        <a class="navbar-item settings modal-trigger" href="<?= WEBROOT ?>/api" title="API">
          <span class="icon is-large">
            <i class="fas fa-code fa-2x"></i>
          </span>
          <span class="desktop-hidden">API</span>
        </a>
        <?php if ($currentUser->isVerified() && $currentUser->isAdmin()): ?>
          <a class="navbar-item settings modal-trigger mobile-toggle" title="Admin Bar" data-target="adminBar">
            <span class="icon is-large">
              <i class="fas fa-user-cog fa-2x"></i>
            </span>
            <span class="desktop-hidden">Admin Bar</span>
          </a>
        <?php endif; ?>
        <span class="navbar-item navbar-last desktop-hidden has-text-klouder" style="font-size: .5rem;">Klouder is cute :3</span>
      </div>
    </div>
  </div>
</nav>
<?php
if ($currentUser->isVerified() && $currentUser->isAdmin()) {
  include_once ROOT . '/resources/includes/adminMenu.php';
}
?>
<!-- End Nav Bar -->