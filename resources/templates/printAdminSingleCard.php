<?php
        $tagList = "";
  foreach($model->getTags() as $tag) {
    if ($tag != ""){
      $tagList .= $tag . ',';
    }
  }
  $tagList = rtrim($tagList, ',');
?>
<form class="single-item admin" id="adminUpdateModel" method="post" action="">
  <input type="hidden" name="redirect" value="<?= $_SERVER['PHP_SELF'] ?>">
  <input type="hidden" name="id" value="<?= $model->getId(); ?>">
  <div class="itemSingle" style="justify-content:flex-start;">
    <!--Model Image-->
    <div class="image card">
  <div class="image card-image is-relative">
    <div class="card-header-icon is-paddingless is-unselectable" style="right:0;position:absolute;z-index:1;cursor:default;margin:.75rem;font-size:2rem;">
        <?php include ROOT . '/resources/components/statusTag.php'; ?>
        </div>
    <figure class="image is-1by1 <?= (in_array('nsfw', $model->getTags())) ? 'nsfw blur' : '' ?>">
      <img src="<?= $model->getImage(); ?>" alt="<?= $model->getName(); ?>">
    </figure>
  </div>
      <div class="card-footer">
        <div class="file is-centered is-fullwidth card-footer-item is-paddingless is-normal">
          <label class="file-label">
          <input type="file" name="image" class="file-input">
          <span class="file-cta" style="border-top-left-radius:0;border-top-right-radius:0;border-top:none;">
            <span class="file-icon">
              <i class="far fa-image"></i>
            </span>
            <span class="file-label">
              Select Image
            </span>
          </span>
        </label>
        </div>
      </div>
    </div>
    <!--!Model Image-->
    <div id="columnInfo">
      <!--Iteminfo Left-->
  <div class="itemInfo">
    <!--Top-->
    <!--<div>-->
      <?php // if(session('active_session') && $model->isAuthor(session('discord_user')->id)): ?>
        
      <?php //endif; ?>
      <div class="field">
      <label class="label">Name</label>
      <div class="control">
        <span class="input disabled"><?= $model->getName(); ?></span>
      </div>
      </div>
        <div class="field">
      <label class="label">Author</label>
      <div class="control">
        <span class="input disabled"><?= $model->getAuthor(); ?></span>
      </div>
      </div>
      
    <!--</div>-->
    <!--!Top-->
    <!--Discord Id-->
    <div class="field">
      <label class="label">User ID</label>
      <div class="control">
        <input class="input" type="text" name="discordId" value="<?= $model->getDiscordId(); ?>" placeholder="discord id as a number">
      </div>
      <!--<p class="help">The associated user's discord id</p>-->
      </div>
    <!--!Discord Id-->
      <!--Status-->
        <div class="field">
            <label class="label">Status</label>
            <div class="control">
            <?php if (!$model->isAuthor($currentUser->getDiscordId())): ?>
              <div class="select input">
                <select name="status">
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
        <!--!Status-->
      
    <!--Tags-->
    <div class="field">
      <label class="label">Tags</label>
      <div class="control">
        <input class="input" type="text" name="tags" value="<?= $tagList ?>" placeholder="tag1,tag2">
      </div>
      </div>
    <!--!Tags-->
    <!--Variation ID-->
    <div class="field">
      <label class="label">Variation ID</label>
      <div class="control">
        <input class="input" type="text" name="variationId" value="<?= $model->getVariationId(); ?>" placeholder="The variation id">
      </div>
      </div>
    <!--!Variation ID-->
    <!--Buttons-->
    <div class="itemButtons">
        <a href="modelsaber://<?= $model->getType() ?>/<?= $model->getId() ?>/<?= $model->getFilename() ?>" class="button model-install">Install</a>
        <a href="<?= $model->getLink() ?>" class="button model-download">Download</a>
        <a onClick="navigator.clipboard.writeText('https://modelsaber.com/'<?= getSuperType($model->getType()) ?>'/?id='<?= $model->getId() ?>)" class="button">Copy Link</a>
      <?php if (!empty($model->getBsaber())): ?>
          <!--<a target="_blank" href="https://bsaber.com/members/<?= $model->getBsaber(); ?>" class="button">BeastSaber Profile</a>-->
      <?php endif; ?>
        <?php if (!empty($model->getDiscordId())): ?>
        <a target="_blank" href="?user=<?= $model->getDiscordId() ?>" class="button">Edit User</a>
      <?php endif; ?>
    </div>
    <!--!Buttons-->
    <div class="field" id="backButton">
      <div class="control has-icons-left">
        <a href="./" class="button is-fullwidth">Back</a>
      <span class="icon is-left"><i class="fas fa-long-arrow-alt-left"></i></span>
    </div>
    </div>
  </div>
      <!--!Iteminfo Left-->
      <!--Iteminfo Right-->
      
      <div class="itemInfo">
        
        <!--Upload Date-->
        <div class="field">
          <label class="label">Upload Date</label>
        <div class="control">
          <span id="uploadTime" class="input disabled"><?= date('Y-m-d H:i:s ', $model->getId()); ?></span>
        </div>
      </div>
        <!--!Upload Date-->
      <!--Hash-->
      <div class="field">
        <label class="label">Hash</label>
        <div class="control">
          <span class="input disabled"><?= $model->getHash(); ?></span>
        </div>
      </div>
      <!--!Hash-->
      <!--Id-->
      <div class="field">
        <label class="label">ID</label>
        <div class="control">
          <span class="input disabled"><?= $model->getId(); ?></span>
        </div>
      </div>
      <!--!id-->
        <!--Discord-->
        <div class="field">
      <label class="label">Discord</label>
      <div class="control">
        <span class="input disabled"><?= $model->getDiscord(); ?></span>
      </div>
      </div>
      <!--!Discord-->
      <!--Bsaber-->
        <div class="field">
      <label class="label">Bsaber</label>
      <div class="control">
        <span class="input disabled"><?= $model->getBsaber()? : 'No bsaber username given'; ?></span>
      </div>
      </div>
      <!--!Bsaber-->
      <!--Variation-->
        <div class="field">
      <label class="label">Variation</label>
      <div class="control">
        <span class="input disabled"><?= $model->getVariationTitle()? : 'No variation title supplied'; ?></span>
      </div>
      </div>
      <!--!Variation-->
      
      <div class="field" id="updateButton">
        <div class="control has-icons-left">
            <?php if (!$model->isAuthor($currentUser->getDiscordId())): ?>
      <!--      <button type="submit" name="action" value="adminUpdateSingle" formaction="<?= WEBROOT ?>/forms/adminUpdateModel.php" class="button is-fullwidth">Update</button>-->
            <button type="button" data-target="uploadModal" class="button is-fullwidth" onclick="document.getElementById('uploadModal').classList.add('is-active')">Update</button>
            <span class="icon is-left"><i class="fas fa-save"></i></span>
          <?php else: ?>
            <button type="button" class="button is-fullwidth disabled" title="You aren't allowed to update your own model's status!">Update</button>
            <span class="icon is-left"><i class="fas fa-save"></i></span>
          <?php endif; ?>
        </div>
      </div>
      
      </div>
      
      <!--!Iteminfo Right-->
    </div>
</div>
  <!--Description-->
  <div class="field" style="margin-top:.75rem;">
      <label class="label">Description</label>
      <div class="control">
  <textarea class="content textarea" name="description" placeholder="Dis div's empty!"><?= $model->getDescription(); ?></textarea>
  </div>
      <p class="help"><?= MARKDOWNSUPPORT; ?></p>
      </div>
  <!--!Description-->
  <!--Modal-->
  <div id="uploadModal" class="modal">
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
      <button type="submit" name="action" value="adminUpdateSingle" formaction="<?= WEBROOT ?>/forms/adminUpdateModel.php" class="button is-fullwidth">Update</button>
      <span class="icon is-left"><i class="fas fa-save"></i></span>
    </div>
    </div>
  </div>
  <button type="button" data-target="uploadModal" class="modal-close is-large" onclick="document.getElementById('uploadModal').classList.remove('is-active')" aria-label="close"></button>
</div>
  <!--!Modal-->
</form>