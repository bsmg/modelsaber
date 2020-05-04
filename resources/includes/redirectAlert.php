<?php
if (isset($_GET['redirect']) && $_GET['redirect'] == "true"): ?>
  <div class="notification is-warning">
    <span class="icon">
      <i class="fas fa-exclamation-triangle"></i> 
    </span>
    <span>The URL has changed to <strong>https://modelsaber.com</strong></span>
  </div>
<?php endif; ?>