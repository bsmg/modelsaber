<?php
if ($model->getDiscordId() == '246478140132687872') {
  $author = str_replace('ie', 'y', $model->getAuthor());
} else {
  $author = $model->getAuthor();
}
$model->readComments();
$modelAuthor = new User();
if ($modelAuthor->read($model->getDiscordId()) !== true) {
  unset($modelAuthor);
}

foreach ($model->getTags() as $tag) {
  if (!empty($tag)) {
    $extra = "";
    if (array_key_exists(strtolower($tag), SPECIALTAGS)) {
      $extra = SPECIALTAGS[strtolower($tag)];
    }
    $tagList[] = ['name' => $tag, 'extra' => $extra];
  }
}

$videoList = $model->getVideo();

$superType = getSuperType($model->getType());
$bsaber = '';
if (isset($modelAuthor) && !empty($modelAuthor->getBsaber())) {
  $bsaber = $modelAuthor->getBsaber();
} else if (!empty($model->getBsaber())) {
  $bsaber = $model->getBsaber();
}

$unityPackageUrl = pathinfo($model->getLink(), PATHINFO_DIRNAME) . '/' . pathinfo($model->getLink(), PATHINFO_FILENAME) . '.unitypackage';
$unityPackagePath = str_replace(WEBROOT, ROOT, $unityPackageUrl);

$unityPackagePath = str_replace('%20', ' ', $unityPackagePath);

$hasUnityPackage = false;
if (file_exists($unityPackagePath)) {
  $hasUnityPackage = true;
}
?>
<form action="<?= WEBROOT ?>/forms/deleteComment.php" method="post" id="deleteComment"></form>
<form action="<?= WEBROOT ?>/forms/updateModel.php" method="post" id="updateModel" enctype="multipart/form-data"></form>
<form action="<?= WEBROOT ?>/forms/voteModel.php" method="post" id="voteModel"></form>
<div class="single-item">
  <input type="hidden" name="redirect" value="<?= $_SERVER['PHP_SELF'] ?>" form="updateModel">
  <input type="hidden" name="redirect" value="<?= $_SERVER['PHP_SELF'] ?>" form="voteModel">
  <input type="hidden" name="platform" value="<?= getDevicePlatform() ?>" form="updateModel">
  <input type="hidden" name="id" value="<?= $model->getId(); ?>" form="updateModel">
  <input type="hidden" name="id" value="<?= $model->getId(); ?>" form="voteModel">
  <div class="itemSingle">
    <div class="image card">
      <div class="image is-relative">
        <div class="card-header-icon is-paddingless is-unselectable" style="right:0;position:absolute;z-index:1;cursor:default;margin:.75rem;font-size:2rem;">
            <?php include ROOT . '/resources/components/statusTag.php'; ?>
        </div>
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
      </div>
      <!--Variation Buttons-->
      <?php if (!empty($model->getVariationId()) && !isset($_GET['edit'])): ?>
        <div class="card-footer fishy">
          <!--Previous Button-->
          <?php if ($model->getPrevious() !== FALSE): ?>
            <a href="<?= $_SERVER['PHP_SELF'] ?>?id=<?= $model->getPrevious() ?>&<?= getDevicePlatform() ?>" class="card-footer-item input is-marginless">
              <span class="icon">
                <i class="fas fa-long-arrow-alt-left"></i>
              </span>
              <span>Previous</span>
            </a>
          <?php else: ?>
            <a class="card-footer-item input disabled is-marginless">
              <span class="icon">
                <i class="fas fa-long-arrow-alt-left"></i>
              </span>
              <span>Previous</span>
            </a>
          <?php endif; ?>
          <!--!Previous Button-->
          <!--Next Button-->
          <?php if ($model->getNext() !== FALSE): ?>
            <a href="<?= $_SERVER['PHP_SELF'] ?>?id=<?= $model->getNext() ?>&<?= getDevicePlatform() ?>" class="card-footer-item input is-marginless">
              <span>Next</span>
              <span class="icon">
                <i class="fas fa-long-arrow-alt-right"></i>
              </span>
            </a>
          <?php else: ?>
            <a class="card-footer-item input disabled is-marginless">
              <span>Next</span>
              <span class="icon">
                <i class="fas fa-long-arrow-alt-right"></i>
              </span>
            </a>
          <?php endif; ?>
          <!--!Next Button-->
        </div>
      <?php endif; ?>
      <!--!Variation Buttons-->
      <!--File Buttons-->
      <?php if (isset($_GET['edit']) && $currentUser->isVerified() && $model->isAuthor($currentUser->getDiscordId())): ?>
        <div class="card-footer fishy">
          <!--File Upload-->
          <div class="file card-footer-item is-paddingless is-fullwidth">
            <label class="file-label">
              <input class="file-input" type="file" accept=".<?= typeToPath($model->getType()); ?>" name="file" form="updateModel">
              <span class="file-cta" style="border: none;">
                <span class="file-icon">
                  <i class="fas fa-upload"></i>
                </span>
                <span class="file-label">
                  Select File
                </span>
              </span>
            </label>
          </div>
          <!--!File Upload-->
          <!--Image Upload-->
          <div class="file card-footer-item is-paddingless is-fullwidth">
            <label class="file-label">
              <input class="file-input" type="file" accept="<?= SUPPORTED_IMAGE_EXTENSIONS ?>" name="image" form="updateModel">
              <span class="file-cta" style="border: none;">
                <span class="file-icon">
                  <i class="far fa-image"></i>
                </span>
                <span class="file-label">
                  Select Image
                </span>
              </span>
            </label>
          </div>
          <!--!Image Upload-->
          <!--Unity Project Upload-->
          <div class="file card-footer-item is-paddingless is-fullwidth">
            <label class="file-label">
              <input class="file-input" type="file" accept=".unitypackage" name="unityproject" form="updateModel">
              <span class="file-cta" style="border: none;">
                <span class="file-icon">
                  <i class="fas fa-file-alt"></i>
                </span>
                <span class="file-label">
                  Select UnityProject
                </span>
              </span>
            </label>
          </div>
          <!--!Unity Project Upload-->
        </div>
      <?php endif; ?>
      <!--!File Buttons-->
    </div>
    <div id="columnInfo" class="show-quest">
      <div class="itemInfo">
        <div>
          <h1 class="title is-1"><span class="<?= ($model->getStatus() !== 'approved' && $model->getApproved() !== true) ? 'down' : ''; ?>"><?= $model->getName(); ?></span></h1>
          
          <h3 class="subtitle is-3">
              <?php if ($model->getDiscordId() != -1): ?>
              <a href="<?= WEBROOT ?>/Profile?user=<?= $model->getDiscordId(); ?>" class="<?= ($model->getStatus() !== 'approved') ? 'down' : ''; ?>"><?= $author; ?></a>
            <?php else: ?>
              <span class="<?= ($model->getStatus() !== 'approved') ? 'down' : ''; ?>"><?= $author; ?></span>
            <?php endif; ?>
          </h3>
        </div>
        <!--Buttons-->
        <?php if (!isset($_GET['edit'])): ?>
          <div class="itemButtons">
            <a href="modelsaber://<?= $model->getType(); ?>/<?= $model->getId(); ?>/<?= $model->getFilename(); ?>" class="button model-install">Install</a>
            <a href="<?= $model->getLink(); ?>" class="button model-download">Download</a>
            <?php if ($hasUnityPackage): ?>
              <a href="<?= $unityPackageUrl ?>" class="button model-download">Download UnityPackage</a>
            <?php endif; ?>
            <a onClick="navigator.clipboard.writeText('https://modelsaber.com/<?= $superType; ?>/?id=<?= $model->getId(); ?>&<?= getDevicePlatform() ?>')" class="button">Copy Link</a>
            <?php if (!empty($bsaber)): ?>
              <a target="_blank" href="https://bsaber.com/members/<?= $bsaber ?>" class="button">BeastSaber Profile</a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
        <!--!Buttons-->
        <?php if (!isset($_GET['edit'])): ?>
        <span><?php include ROOT . "/resources/components/discordTag.php"; ?></span>
        <?php endif; ?>
        <div>
          <!--Hash-->
          <div class="field quest-hidden">
            <label class="label">Hash</label>
            <div class="control">
              <span class="input disabled"><?= $model->getHash(); ?></span>
            </div>
          </div>
          <!--!Hash-->
          <!--ID-->
          <div class="field quest-hidden">
            <label class="label">ID</label>
            <div class="control">
              <span class="input disabled"><?= get('id'); ?></span>
            </div>
          </div>
          <!--!ID-->
        </div>
        <!--Tags-->
        <?php if (isset($_GET['edit']) && $currentUser->isVerified() && $model->isAuthor($currentUser->getDiscordId())): ?>
          <div class="field">
            <label class="label">Tags</label>
            <div class="control">
              <textarea class="textarea no-hover" name="tags" form="updateModel" placeholder="tag1,tag2,etc."><?= implode(',', $model->getTags()); ?></textarea>
            </div>
          </div>
        <?php else: ?>
          <label class="label">Tags</label>
          <div class="tags">
              <?php if (isset($tagList)): ?>
            <?php foreach ($tagList as $tag): ?>
              <span class="tag is-rounded <?= $tag['extra'] ?>" onclick="addFilter('tag:' + this.innerHTML)"><?= $tag['name'] ?></span>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        <?php endif; ?>
        <!--!Tags-->
        <div class="field has-addons">
<!--          <div class="control">
            <button class="button">
              <span class="icon">
                <i class="fas fa-download"></i>
              </span>
              <span><?= $model->getDownloads() ?></span>
            </button>
          </div>-->
          <div class="control">
            <button class="button <?= ($currentUser->getVote($model->getId()) === TRUE) ? 'has-text-klouder' : '' ?>" form="voteModel" name="vote" value="upvote">
              <span class="icon">
                <i class="fas fa-thumbs-up"></i>
              </span>
              <span><?= $model->getUpvotes() ?></span>
            </button>
          </div>
          <div class="control">
            <button class="button <?= ($currentUser->getVote($model->getId()) === FALSE) ? 'has-text-klouder' : '' ?>" form="voteModel" name="vote" value="downvote">
              <span class="icon">
                <i class="fas fa-thumbs-down"></i>
              </span>
              <span><?= $model->getDownvotes() ?></span>
            </button>
          </div>
        </div>
        <!--Action Buttons-->
        <div class="field is-grouped">
          <div class="control">
            <a href="./" class="button"><i class="fas fa-long-arrow-alt-left"></i><span>Back</span></a>
          </div>
          <div class="control">
            <?php if ($currentUser->isVerified() && $model->isAuthor($currentUser->getDiscordId()) && !isset($_GET['edit'])): ?>
              <a href="?id=<?= $model->getId(); ?>&<?= getDevicePlatform() ?>&edit" class="button"><i class="fas fa-pen"></i><span>Edit</span></a>
            <?php endif; ?>
          </div>
          <div class="control">
              
            <?php if ($currentUser->isVerified() && $currentUser->isAdmin() && !isset($_GET['edit'])): ?>
              <a href="<?= WEBROOT ?>/Manage?id=<?= $model->getId(); ?>" class="button"><i class="fas fa-pen"></i><span>Edit as admin</span></a>
            <?php endif; ?>
          </div>
          <div class="control">
              
            <?php if ($currentUser->isVerified() && $model->isAuthor($currentUser->getDiscordId()) && isset($_GET['edit'])): ?>
              <button type="submit" form="updateModel" class="button" name="action" value="<?= get('id'); ?>">Submit</button>
            <?php endif; ?>
          </div>
        </div>
        <!--!Action Buttons-->
      </div>
    </div>
  </div>
  <!--Description-->
  <?php if (isset($_GET['edit']) && $currentUser->isVerified() && $model->isAuthor($currentUser->getDiscordId())): ?>
    <hr>
    <div class="field">
      <label class="label">Description</label>
      <div class="control">
        <textarea class="textarea no-hover" name="description" form="updateModel"><?= $model->getDescription(); ?></textarea>
      </div>
      <p class="help"><?= MARKDOWNSUPPORT ?></p>
    </div>
  <?php elseif (!empty($model->getDescription())): ?>
    <hr>
    <div class="content">
        <?php foreach (explode("\n", $model->getDescription()) as $line): ?>
          <?= $parsedown->text($line); ?>
        <?php endforeach; ?>
    </div>
  <?php endif; ?>
  <!--!Description-->
  <!--Comments-->
  <?php if (!isset($_GET['edit'])): ?>
    <hr>
    <h3 class="subtitle is-4">Comments</h3>
    <?php if (empty($model->getComments())): ?>
      <h4 class="subtitle is-5">This model doesn't have any comments yet...</h4>
    <?php endif; ?>
      
    <?php if ($currentUser->canComment()): ?>
      <?php include ROOT . '/resources/components/postComment.php'; ?>
    <?php else: ?>
      <span>log in to comment...</span>
    <?php endif; ?>
      
    <?php if (!empty($model->getComments())): ?>
      <div id="comments">
        <?php
        foreach ($model->getComments() as $comment) {
          $commentor = new User();
          $commentor->getComment($comment, $model->getDiscordId());
          unset($commentor);
        }
        ?>
      </div>
    <?php endif; ?>
      
  <?php endif; ?>
  <!--!Comments-->
</div>
<?php
unset($modelAuthor);
?>