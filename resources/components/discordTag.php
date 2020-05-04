<?php if (!empty($model->getDiscord())): ?>
  <div class="tags has-addons discord-tag">
    <span class="tag no-hover">
      <span class="icon">
        <i class="fab fa-discord has-text-white" style="font-size: 16px; width: auto;"></i>
      </span>
    </span>
    <span class="tag no-hover"><?= $model->getDiscord(); ?></span>
  </div>
<?php endif; ?>