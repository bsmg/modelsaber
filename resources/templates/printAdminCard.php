<?php
global $positive;
global $positiveLabel;
global $negative;
global $negativeLabel;
$tagList = "";
  foreach($model->getTags() as $tag) {
    if ($tag != ""){
      $tagList .= $tag . ',';
    }
  }
  $tagList = rtrim($tagList, ',');
  //Should be used in the item class whenever i can get the pagination to work correctly
  /* data-page="<?= $model->getCurrentPage(); ?>" */
?>
<form class="item" action="" method="post" id="<?= $model->getId(); ?>">
  <input type="hidden" name="redirect" value="<?= WEBROOT ?>/Manage">
  <input type="hidden" name="id" value="<?= $model->getId(); ?>">
  <input type="hidden" name="page" value="<?= $current_page; ?>">
  <div class="card">
    <div class="card-header is-shadowless" style="position:absolute;top:0;width:100%;border-radius:6px 0;background-color:transparent;justify-content:flex-end;">
      <div class="card-header-icon is-paddingless is-unselectable" style="z-index:1;cursor:default;margin:.75rem;">
        <?php include ROOT . '/resources/components/statusTag.php'; ?>
      </div>
    </div>
    <div class="card-image">
      <a href="<?= WEBROOT ?>/Manage?id=<?= $model->getId(); ?>">
      <figure class="image is-1by1 <?= (in_array('nsfw', $model->getTags())) ? 'nsfw blur' : '' ?>">
        <img src="<?= $model->getImage(); ?>" alt="<?= $model->getName(); ?>">
      </figure>
      </a>
    </div>
    <div class="card-content-admin">
      <div class="media">
        <div class="media-content">
          <?php // if (adminCookie('showName') == "checked"): ?>
          <div class="field <?= (adminCookie('showName') !== "checked")? "is-hidden" : ""; ?>">
            <label class="label">Name</label>
            <div class="control">
              <span class="input disabled"><?= $model->getName(); ?></span>
            </div>
          </div>
          <?php // endif; ?>
          <?php // if (adminCookie('showAuthor') == "checked"): ?>
          <div class="field <?= (adminCookie('showAuthor') !== "checked")? "is-hidden" : ""; ?>">
            <label class="label">Author</label>
            <div class="control">
              <span class="input disabled"><?= $model->getAuthor(); ?></span>
            </div>
          </div>
          <?php // endif; ?>
          <?php // if (adminCookie('showTags') == "checked"): ?>
          <div class="field <?= (adminCookie('showTags') !== "checked")? "is-hidden" : ""; ?>">
            <label class="label">Tags</label>
            <div class="control">
              <input class="input" type="text" name="tags" value="<?= $tagList ?>" form="<?= $model->getId(); ?>" placeholder="no,tags,specified">
            </div>
          </div>
          <?php // endif; ?>
          <?php // if (adminCookie('showHash') == "checked"): ?>
          <div class="field mobile-hidden <?= (adminCookie('showHash') !== "checked")? "is-hidden" : ""; ?>">
            <label class="label">Hash</label>
            <div class="control">
              <span class="input disabled" placeholder="this is concerning, the hash is empty."><?= $model->getHash(); ?></span>
            </div>
          </div>
          <?php // endif; ?>
          <?php // if (adminCookie('showStatus') == "checked"): ?>
          <div class="field <?= (adminCookie('showStatus') !== "checked")? "is-hidden" : ""; ?>">
            <label class="label">Status</label>
            <div class="control">
              <?php if (!$model->isAuthor($currentUser->getDiscordId())): ?>
              <div class="select input">
                <select name="status" form="<?= $model->getId(); ?>">
                  <?php foreach (STATUSLIST as $option): ?>
                  <option value="<?= strtolower($option); ?>" <?= ($model->getStatus() == strtolower($option))? 'selected' : '' ?>><?= ucfirst($option); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <?php else: ?>
              <span class="input disabled" placeholder="This should not be empty."><?= ucfirst($model->getStatus()) ?></span>
              <?php endif; ?>
            </div>
          </div>
<?php // endif; ?>
          <?php // if (adminCookie('showDescription') == "checked"): ?>
          <div class="field <?= (adminCookie('showDescription') !== "checked")? "is-hidden" : ""; ?>">
            <label class="label">Description</label>
            <div class="control">
              <textarea class="textarea" type="text" name="description" form="<?= $model->getId(); ?>" placeholder="no description specified"><?= $model->getDescription(); ?></textarea>
            </div>
            <p class="help"><?= MARKDOWNSUPPORT; ?></p>
          </div>
<?php // endif; ?>
        </div>
      </div>
    </div>
    <!-- <form action="index.php" method="POST" id="<?= $negative . '-' . $model->getId(); ?>">
      <input type="hidden" name="mode" value="<?= $negative ?>">
      <input type="hidden" name="type" value="<?= $model->getType(); ?>">
      <input type="hidden" name="id" value="<?= $model->getId(); ?>">
      <input type="hidden" name="name" value="<?= $model->getName(); ?>">
      <input type="hidden" name="author" value="<?= $model->getAuthor(); ?>">
      <input type="hidden" name="tags" value="<?= $tagList ?>">
      <input type="hidden" name="bsaber" value="<?= $model->getBsaber(); ?>">
      <input type="hidden" name="comments" value="<?= $model->getDescription(); ?>">
    </form>
    <form action="index.php" method="POST" id="<?= $positive . '-' . $model->getId(); ?>">
      <input type="hidden" name="mode" value="<?= $positive ?>">
      <input type="hidden" name="type" value="<?= $model->getType(); ?>">
      <input type="hidden" name="oldName" value="<?= $model->getName(); ?>">
      <input type="hidden" name="id" value="<?= $model->getId(); ?>">
      <input type="hidden" name="image" value="<?= $model->getImage(); ?>">
      <input type="hidden" name="link" value="<?= $model->getLink(); ?>">
    </form> -->
    <div class="card-footer mobile-hidden">
      <a href="modelsaber://<?= $model->getType() . '/' . $model->getId() . '/' . $model->getFilename(); ?>" class="card-footer-item model-install">Install</a>
      <a href="<?= $model->getLink(); ?>" class="card-footer-item model-download">Download</a>
    </div>
    <div class="card-footer">
      <a href="<?= WEBROOT . '/' . getSuperType($model->getType()); ?>/?id=<?= $model->getId(); ?>" class="card-footer-item model-details">View</a>
<!--      <button class="card-footer-item model-update" formaction="<?= WEBROOT ?>/forms/adminUpdateModel.php" type="submit" name="action" value="adminUpdate" form="<?= $model->getId(); ?>">Update</button>-->
      <button class="card-footer-item model-update" type="button" onclick="document.getElementById('uploadModal-<?= $model->getId(); ?>').classList.add('is-active')">Update</button>
      <!--<a onclick="document.getElementById('<?= $positive . '-' . $model->getId(); ?>').submit()" class="card-footer-item"><?= $positiveLabel ?></a>-->
      <!-- <a onclick="document.getElementById('<?= $negative . '-' . $model->getId(); ?>').submit()" class="card-footer-item"><?= $negativeLabel ?></a> -->
    </div>
  </div>
  <!--Modal-->
  <div id="uploadModal-<?= $model->getId(); ?>" class="modal">
  <div class="modal-background"></div>
  <div class="modal-content">
    <!--Notification-->
  <div class="field">
      <label class="label">Notification Message</label>
      <div class="control">
  <textarea class="textarea" name="notifMessage" placeholder="A message to be shown in the user's inbox"></textarea>
  </div>
      <p class="help"><?= MARKDOWNSUPPORT; ?></p>
      </div>
  <!--!Notification-->
    <div class="field" id="updateButton">
    <div class="control has-icons-left">
      <!--<button type="submit" name="action" value="adminUpdateSingle" formaction="<?= WEBROOT ?>/forms/adminUpdateModel.php" class="button is-fullwidth">Update</button>-->
      <button class="button is-fullwidth" formaction="<?= WEBROOT ?>/forms/adminUpdateModel.php" type="submit" name="action" value="adminUpdate" form="<?= $model->getId(); ?>">Update</button>
      <span class="icon is-left"><i class="fas fa-save"></i></span>
    </div>
    </div>
  </div>
  <button type="button" data-target="uploadModal" class="modal-close is-large" onclick="document.getElementById('uploadModal-<?= $model->getId(); ?>').classList.remove('is-active')" aria-label="close"></button>
</div>
  <!--!Modal-->
</form>