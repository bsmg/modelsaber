<!-- Start Tabs -->
<div class="tabs is-centered is-boxed">
  <ul>
    <li <?= getDeviceTab(AVATARS); ?>>
      <a href="<?= WEBROOT ?>/<?= ucfirst(AVATARS); ?>?<?= getDevicePlatform() ?>">
        <span class="icon is-small"><i class="fas fa-hat-wizard" aria-hidden="true"></i></span>
        <span><?= ucfirst(AVATARS); ?></span>
      </a>
    </li>
    <li <?= getDeviceTab(SABERS); ?>>
      <a href="<?= WEBROOT ?>/<?= ucfirst(SABERS); ?>?<?= getDevicePlatform() ?>">
        <span class="icon is-small"><i class="fas fa-magic" aria-hidden="true"></i></span>
        <span><?= ucfirst(SABERS); ?></span>
      </a>
    </li>
    <li <?= getDeviceTab(PLATFORMS); ?>>
      <a href="<?= WEBROOT ?>/<?= ucfirst(PLATFORMS); ?>?<?= getDevicePlatform() ?>">
        <span class="icon is-small"><i class="fas fa-torii-gate" aria-hidden="true"></i></span>
        <span><?= ucfirst(PLATFORMS); ?></span>
      </a>
    </li>
    <li <?= getDeviceTab(NOTES); ?>>
      <a href="<?= WEBROOT ?>/<?= ucfirst(NOTES); ?>?<?= getDevicePlatform() ?>">
        <span class="icon is-small" style="fill: currentColor;"><?php include ROOT . '/resources/Bloq.svg'; ?></span>
        <span><?= ucfirst(NOTES); ?></span>
      </a>
    </li>
    <li <?= getDeviceTab(TRAILS); ?>>
      <a href="<?= WEBROOT ?>/<?= ucfirst(TRAILS); ?>?<?= getDevicePlatform() ?>">
        <span class="icon is-small" style="fill: currentColor;"><?php include ROOT . '/resources/trail.svg'; ?></span>
        <span><?= ucfirst(TRAILS); ?></span>
      </a>
    </li>
    <li <?= getDeviceTab(SIGNS); ?>>
      <a href="<?= WEBROOT ?>/<?= ucfirst(SIGNS); ?>?<?= getDevicePlatform() ?>">
        <span class="icon is-small" style="fill: currentColor;"><?php include ROOT . '/resources/sign.svg'; ?></span>
        <span><?= ucfirst(SIGNS); ?></span>
      </a>
    </li>
    <li <?= getDeviceTab(MISCS); ?>>
      <a href="<?= WEBROOT ?>/<?= ucfirst(MISCS); ?>?<?= getDevicePlatform() ?>">
        <span class="icon is-small"><i class="fas fa-shapes" aria-hidden="true"></i></span>
        <span><?= ucfirst(MISCS); ?></span>
      </a>
    </li>
  </ul>
</div>
<!-- End Tabs -->