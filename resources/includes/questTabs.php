<!-- Start Tabs -->
    <div class="tabs is-centered is-boxed">
      <ul>
        <?php if (array_key_exists(ucfirst(AVATARS), MOBILETABS)): ?>
        <li <?= ($_SERVER['PHP_SELF'] == WEBROOT . '/Quest/' . ucfirst(AVATARS) . '/index.php') ? 'class="is-active"' : ''; ?>>
          <a href="<?= (ENV == 'local') ? WEBROOT : ''; ?>/<?= ucfirst(AVATARS); ?>">
            <span class="icon is-small"><i class="fas fa-hat-wizard" aria-hidden="true"></i></span>
            <span><?= ucfirst(AVATARS); ?></span>
          </a>
        </li>
        <?php endif; ?>
        <?php if (array_key_exists(ucfirst(SABERS), MOBILETABS)): ?>
        <li <?= ($_SERVER['PHP_SELF'] == WEBROOT . '/Quest/' . ucfirst(SABERS) . '/index.php') ? 'class="is-active"' : ''; ?>>
          <a href="<?= (ENV == 'local') ? WEBROOT : ''; ?>/<?= ucfirst(SABERS); ?>">
            <span class="icon is-small"><i class="fas fa-magic" aria-hidden="true"></i></span>
            <span><?= ucfirst(SABERS); ?></span>
          </a>
        </li>
        <?php endif; ?>
        <?php if (array_key_exists(ucfirst(PLATFORMS), MOBILETABS)): ?>
        <li <?= ($_SERVER['PHP_SELF'] == WEBROOT . '/Quest/' . ucfirst(PLATFORMS) . '/index.php') ? 'class="is-active"' : ''; ?>>
          <a href="<?= (ENV == 'local') ? WEBROOT : ''; ?>/<?= ucfirst(PLATFORMS); ?>">
            <span class="icon is-small"><i class="fas fa-torii-gate" aria-hidden="true"></i></span>
            <span><?= ucfirst(PLATFORMS); ?></span>
          </a>
        </li>
        <?php endif; ?>
        <?php if (array_key_exists(ucfirst(NOTES), MOBILETABS)): ?>
        <li <?= ($_SERVER['PHP_SELF'] == WEBROOT . '/Quest/' . ucfirst(NOTES) . '/index.php') ? 'class="is-active"' : ''; ?>>
          <a href="<?= (ENV == 'local') ? WEBROOT : ''; ?>/<?= ucfirst(NOTES); ?>">
            <span class="icon is-small" style="fill: currentColor;"><?php include ROOT . '/resources/noteWeb.svg'; ?></span>
            <span><?= ucfirst(NOTES); ?></span>
          </a>
        </li>
        <?php endif; ?>
      </ul>
    </div>
    <!-- End Tabs -->