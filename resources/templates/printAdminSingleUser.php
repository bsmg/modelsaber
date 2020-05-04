<?php
//global $user;
        $roleList = "";
  foreach($user->getRoles() as $role) {
    if ($role->getId() != ""){
      $roleList .= $role->getId() . ',';
    }
  }
  $roleList = rtrim($roleList, ',');
?>
<form class="single-item admin" style="width:100%;" id="adminUpdateUser" method="post" action="">
  <input type="hidden" name="redirect" value="<?= $_SERVER['PHP_SELF'] ?>">
  <input type="hidden" name="id" value="<?= $user->getDiscordId(); ?>">
  <div class="itemSingle" style="justify-content:flex-start;">
    <!--Model Image-->
<!--    <div class="image card">
  <div class="image card-image is-relative">
    <div class="card-header-icon is-paddingless is-unselectable" style="right:0;position:absolute;z-index:1;cursor:default;margin:.75rem;font-size:2rem;">
        <?php //include ROOT . '/resources/components/statusTag.php'; ?>
        </div>
    <figure class="image is-1by1">
      <img src="<?php // $model->getImage() ?>" alt="<?php // $model->getName() ?>">
    </figure>
  </div>
      <div class="card-footer">
        <div class="file is-centered is-fullwidth card-footer-item is-paddingless is-normal">
        <label class="file-label">
          <input type="file" name="image" class="file-input">
          <span class="file-cta" style="border-top-left-radius:0;border-top-right-radius:0;">
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
    </div>-->
    <!--!Model Image-->
    <div id="columnInfo">
      <!--Iteminfo Left-->
      <div class="itemInfo">
    <!--Top-->
    <!--<div>-->
      <?php // if(session('active_session') && $model->isAuthor(session('discord_user')->id)): ?>
        
      <?php //endif; ?>
    <label class="label">Username</label>
      <div class="field has-addons">
      
      <div class="control is-expanded">
        <input class="input" type="text" name="username" value="<?= $user->getUsername(); ?>" placeholder='username'>
      </div>
      <div class="control" style='width: initial;'>
        <span class="input disabled">#</span>
      </div>
      <div class="control">
        <input class="input" type="text" pattern="\d{4}" maxlength='4' name="discriminator" value="<?= $user->getDiscriminator(); ?>" placeholder="1234">
      </div>
      </div>
      
    <!--</div>-->
    <!--!Top-->
    <!--Bsaber-->
    <div class="field">
      <label class="label">Bsaber profile</label>
      <div class="control">
        <input class="input" type="text" name="bsaber" value="<?= $user->getBsaber(true); ?>" placeholder="the link to their bsaber profile">
      </div>
      </div>
    <!--!Bsaber-->
      
    <!--Roles-->
    <label class="label">Roles</label>
    <div class="field has-addons">
      
      <div class="control is-expanded">
        <?php if ($currentUser->canUpdateUserRoles()): ?>
          <input id="roles" class="input" type="text" name="roles" value="<?= $roleList ?>" placeholder="role1,role2">
        <?php else: ?>
          <span class="input disabled"><?= (!empty($roleList)) ? $roleList: 'role1,role2'; ?></span>
        <?php endif; ?>
      </div>
      <div class="control">
        
        <?php if ($currentUser->canUpdateUserRoles()): ?>
          <button class="button" type="button" onclick="document.getElementById('rolePickerModal').classList.add('is-active')">Pick</button>
        <?php else: ?>
          <span class="button disabled">Pick</span>
        <?php endif; ?>
      </div>
    </div>
    <!--!Roles-->
    <!--Buttons-->
    <div class="itemButtons">
      <?php if (!empty($user->getBsaber())): ?>
          <a target="_blank" href="https://bsaber.com/members/<?= $user->getBsaber(); ?>" class="button">BeastSaber Profile</a>
      <?php endif; ?>
    </div>
    <!--!Buttons-->
  </div>
      <!--!Iteminfo Left-->
      <!--Iteminfo Right-->
      
      <div class="itemInfo">
        
        <!--Creation Date-->
        <!--Not implemented, probably won't implement-->
        <div class="field is-hidden">
          <label class="label">Upload Date</label>
        <div class="control">
          <span id="creationTime disabled" class="input disabled"><?= date('Y-m-d H:i:s ', time()); ?></span>
        </div>
      </div>
        <!--!Upload Date-->
      <!--Discord Id-->
      <div class="field">
        <label class="label">Discord ID</label>
        <div class="control">
          <span class="input disabled"><?= $user->getDiscordId(); ?></span>
        </div>
      </div>
      <!--!Discord Id-->
      <!--Verified-->
      <div class="field">
        <label class="label">Is verified?</label>
        <div class="control">
          <span class="input disabled"><?= $user->getVerified() ? "yes" : "no"; ?></span>
        </div>
      </div>
      <!--!Verified-->
      <div class="field" id="updateButton">
    
    </div>
      </div>
      
      <!--!Iteminfo Right-->
    </div>
</div>
  <!--Description-->
  <div class="field" style="margin-top:.75rem;">
      <label class="label">Description</label>
      <div class="control">
  <textarea class="content textarea" name="description" placeholder="Dis div's empty!"><?= $user->getDescription(); ?></textarea>
  </div>
<!--      <p class="help">Supported formatting tags include: <?= htmlspecialchars(SUPPORTEDTAGSFORMATED); ?></p>-->
      <p class="help"><?= MARKDOWNSUPPORT; ?></p>
      </div>
  <!--!Description-->
  <!--Form Buttons-->
  <div class="field is-grouped">
    <div id="backButton" class="control has-icons-left is-expanded">
        <a href="./" class="button is-fullwidth">Back</a>
      <span class="icon is-left"><i class="fas fa-long-arrow-alt-left"></i></span>
    </div>
    <div class="control has-icons-left is-expanded">
      <button type="submit" name="action" value="<?= $user->getDiscordId(); ?>" formaction="<?= WEBROOT ?>/forms/adminUpdateUser.php" class="button is-fullwidth">Update</button>
      <span class="icon is-left"><i class="fas fa-save"></i></span>
    </div>
    </div>
  <!--!Form Buttons-->
  <?php include_once ROOT . '/resources/components/rolePicker.php'; ?>
</form>