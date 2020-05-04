<?php
$modelUser = new User();
$modelUser->read($model->getDiscordId());
$author = $model->getAuthor();
if ($modelUser->isBobby()) {
  $author = $modelUser->bobbyfy($author);
}
$modelStatus = ($model->getStatus() !== 'approved')? 'down' : '' ;

$superType = getSuperType($model->getType());

$videoList = $model->getVideo();

$tagList = implode(',', array_unique(array_filter($model->getTags())));
$tagList = rtrim($tagList, ',');
$tagList = strtolower($tagList);
?>
<div class="item" data-name="<?= $model->getName(); ?>" data-author="<?= $model->getAuthor(); ?>" data-id="<?= $model->getId(); ?>" data-tags="<?= $tagList; ?>">
  <div class="card">
    <div class="card-header is-unselectable is-shadowless" style="position:absolute;top:0;width:100%;border-radius:6px 0;background-color:transparent;justify-content:flex-end;">
      <div class="card-header-icon is-paddingless is-unselectable" style="z-index:2;cursor:default;margin:.75rem;">
          <?php include ROOT . '/resources/components/statusTag.php'; ?>
      </div>
    </div>
    <a class="card-image is-relative" href="<?= WEBROOT . '/' . $superType; ?>/?id=<?= $model->getId(); ?>&<?= getDevicePlatform(); ?>">
      <figure class="image is-1by1 <?= (in_array('nsfw', $model->getTags())) ? 'nsfw blur' : '' ?>">
        <?php if ($model->hasVideoThumbnail()): ?>
          <video width="1024" height="1024" autoplay loop muted poster="<?= $model->getImage(); ?>" style="position: absolute; top: 0;">
            <?php foreach ($videoList as $video): ?>
            <source src="<?= $video ?>" type="video/<?= substr($video, strrpos($video, '.') + 1) ?>">
            <?php endforeach; ?>
          </video>        
        <?php else: ?>
          <img src="<?= $model->getImage(); ?>" alt="<?= $model->getName(); ?>">
        <?php endif; ?>
      </figure>
    </a>
    <div class="card-content-container">
      <!-- <div class="card-content" id="card-link-<?= $model->getId() ?>" onclick="location.href='<?= WEBROOT . '/' . $superType; ?>/?id=<?= $model->getId(); ?>&<?= getDevicePlatform(); ?>'" style="cursor:pointer;"> -->
      <div class="card-content" style="cursor:pointer;">
        <div class="media">
          <div class="media-content">
            <p class="title is-4"><span class="<?= $modelStatus ?>"><?= $model->getName(); ?></span></p>
            <p class="subtitle is-6">
              <?php if ($model->getDiscordId() != -1): ?>
                <a href="<?= WEBROOT ?>/Profile?user=<?= $model->getDiscordId(); ?>" class="<?= $modelStatus; ?> <?= $modelUser->getNameColor(); ?>"><?= $author; ?></a>
              <?php else: ?>
                <span class="<?= $modelStatus ?>"><?= $author; ?></span>
              <?php endif; ?>
              <?php if (!empty($model->getVariationId())): ?>
                <br>
                <a href=".\?variation=<?= $model->getVariationId(); ?>">
                    <?= (!empty($model->getVariationTitle())) ? $model->getVariationTitle() : "Unamed Variation"; ?>
                </a>
              <?php endif; ?>
            </p>
          </div>
        </div>
        <div class="tags">
            <?php
            foreach ($model->getTags() as $tag) {
              if (!empty($tag)) {
                $extra = "";
                if (array_key_exists(strtolower($tag), SPECIALTAGS)) {
                  $extra = SPECIALTAGS[strtolower($tag)];
                }
                echo '<span class="tag is-rounded ' . $extra . '" onclick="filterAdd(\'tag:\' + this.innerHTML)">' . $tag . '</span>';
              }
            }
            ?>
        </div>
      </div>
    </div>
    <div class="card-footer">
      <?php
      if (getDevicePlatform() == 'quest') {
        $install = 'sidequest';
      } else {
        $install = 'modelsaber';
      }
      ?>
      <a href="<?= $install ?>://<?= $model->getType(); ?>/<?= $model->getId(); ?>/<?= $model->getFilename(); ?>" class="card-footer-item model-install mobile-hidden">Install</a>
      <a href="<?= $model->getLink(); ?>" class="card-footer-item model-download quest-hidden mobile-hidden">Download</a>
      <a href="<?= WEBROOT . '/' . $superType; ?>/?id=<?= $model->getId(); ?>&<?= getDevicePlatform() ?>" class="card-footer-item model-details mobile-only-child">Details</a>
    </div>
  </div>
</div>
<?php
unset($modelUser, $modelStatus, $tagList, $superType, $videoList);
?>