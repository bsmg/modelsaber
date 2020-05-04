<div id="adminBar">
  <div class="container">
    
    <a class="navbar-item" href="<?= WEBROOT ?>/Manage/Log" title="Logs">
      <span class="icon is-large">
        <i class="fas fa-file-alt fa-2x"></i>
      </span>
      <span>Logs</span>
    </a>
    
    <a class="navbar-item settings modal-trigger" href="<?= WEBROOT ?>/Manage?status=<?= UNAPPROVED ?>&type=all" title="Manage Models">
      <span class="icon is-large">
        <i class="fas fa-dice-d6 fa-2x"></i>
      </span>
      <span>Models</span>
    </a>
    <a class="navbar-item settings modal-trigger" href="<?= WEBROOT ?>/Manage/Users" title="Manage Users">
      <span class="icon is-large">
        <i class="fas fa-user fa-2x"></i>
      </span>
      <span>Users</span>
    </a>
    <?php if (strpos($_SERVER['PHP_SELF'], '/Manage') && strpos($_SERVER['PHP_SELF'], '/Manage/Log') === FALSE): ?>
      <a class="navbar-item mobile-toggle" data-target="shownFields" title="Manage settings">
        <span class="icon is-large">
          <i class="fas fa-filter fa-2x"></i>
        </span>
        <span>Settings</span>
      </a>
    <?php endif; ?>
  </div>
</div>