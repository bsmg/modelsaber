<a role="button" class="icon info mobile-toggle has-text-info is-<?= $size ?>" title="<?= ($isFile)? "Click for info" : addslashes($message); ?>" data-target="info-<?= $infoIndex ?>">
  <i class="fas fa-info-circle" style="font-size: <?= $fontsize ?>">
  </i>
</a>
<span class="infoText is-info is-small notification" id="info-<?= $infoIndex ?>" aria-expanded="false"><?php if ($isFile) { include $message; } else { echo addslashes($message); } ?></span>