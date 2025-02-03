<?php
require_once '../resources/includes/constants.php';
if (!$currentUser->isVerified()) {
  HTTPError(403);
}
if (!$currentUser->canUpload()) {
  HTTPError(403);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once '../head.php'; ?>
<!--Start OEmbed--> 
  <meta content="ModelSaber" property="og:site_name">
  <meta content="Upload" property="og:title">
  <meta content="Upload your own models" property="og:description">
  <meta content="#ebf4f9" name="theme-color">
  <meta content="/resources/upload.png" property="og:image">
<!--End OEmbed--> 
</head>
<body>
<?php include_once ROOT . '/resources/includes/menu.php'; ?>
<section class="section">
  <div class="container">
    <!-- Start Tabs -->
    <div class="tabs is-centered is-boxed">
      <ul>
        <li class="is-active">
          <a href="<?= WEBROOT ?>/Upload?<?= getDevicePlatform() ?>">Simple upload</a>
        </li>
        <li>
          <a href="<?= WEBROOT ?>/Upload/Advanced?<?= getDevicePlatform() ?>">Advanced upload</a>
        </li>
      </ul>
    </div>
    <!-- End Tabs -->
    <div class="content">
      <div>
        <h4 class="title is-4">Guidelines:</h4>
        <ul>
          <li><strong>General:</strong></li>
          <ul>
            <li><strong>NEVER</strong> rip assets or upload a model without the original author's consent. We do <strong>NOT</strong> condone piracy in any way or form!</li>
          </ul>
          <li><strong>Thumbnails:</strong></li>
          <ul>
            <li><strong>MUST</strong> have a <strong>1x1 aspect ratio</strong>.</li>
            <li>Must be <strong>tasteful</strong> and <strong>appropiate</strong>.</li>
            <li><strong>MUST</strong> have the same filename as the model file. <?php info("When uploading mutiple files this still aplies") ?></li>
            <li>Thumbnails must be <b>at least</b> <?= MINIMAGESIZE; ?> by <?= MINIMAGESIZE; ?> pixels.</li>
            <li>Can be of the image types .png, .jpeg, .svg, and .gif or of the video types .mp4 and .webm.</li>
            <li>The max filesize of a video is <?= MAXVIDEOFILESIZE ?> MB!</li>
          </ul>
          <li><strong>Tags:</strong></li>
          <ul>
            <li>Open the <kbd>Tag Picker&trade;</kbd> to select, deselect, or add tags.</li>
            <li>Only <strong>one (1)</strong> tag per field, press the <kbd>Add tag</kbd> button to add more tags.</li>
            <li>Do <strong>NOT</strong> inlcude commas <kbd>,</kbd> in your tags.</li>
            <li>Make your tags <strong>descriptive</strong>, specially of the item's <strong>capabilities</strong> and <strong>limitations</strong>.</li>
          </ul>
        </ul>
      </div>
      <hr />
      <form class="is-relative" action="upload.php" method="POST" id="uploadForm" enctype="multipart/form-data">
        <input type="hidden" name="formType" value="simple">
        <?php require_once ROOT . '/resources/components/licenseSelector.php'; ?>
        
        <label class="label is-medium">Description (optional)</label>
        <div class="field flex" style="display: block;">
          <div class="control">
            <textarea class="textarea" name="comments" form="uploadForm" type="text" placeholder="Comments"></textarea>
          </div>
          <p class="help"><?= MARKDOWNSUPPORT ?></p>
        </div>
        
        <label class="label is-medium">Tags</label>
        <div class="field is-grouped is-grouped-multiline" id="tag-container">
          <div class="field flex">
            <div class="control has-icons-left">
              <button onclick="document.getElementById('tagPickerModal').classList.add('is-active')" form="none" class="button is-fullwidth" name="tags" form="uploadForm" placeholder="Tag">Open Tag Picker&trade;</button>
              <span class="icon is-left">
                <i class="fas fa-tags"></i>
              </span>
            </div>
          </div>
        </div>
        
        <label class="label is-medium">File</label>
        <div class="field flex has-addons file">
          <div class="control">
            <label class="file-label">
              <input class="file-input" type="file" accept="<?= $helper->getFileExtensions() ?>" name="file[]" form="uploadForm" required>
              <span class="file-cta">
                <span class="file-icon">
                  <i class="fas fa-upload"></i>
                </span>
                <span class="file-label">
                  Select File
                </span>
              </span>
            </label>
          </div>
          
          <div class="control">
            <label class="file-label">
              <input class="file-input" type="file" accept="<?= SUPPORTED_IMAGE_EXTENSIONS ?>" name="image" form="uploadForm" required>
              <span class="file-cta">
                <span class="file-icon">
                  <i class="far fa-image"></i>
                </span>
                <span class="file-label">
                  Select Thumbnail
                </span>
              </span>
            </label>
          </div>
          
          <div class="control">
            <button class="button upload-button" type="submit" form="uploadForm">
              Upload
            </button>
          </div>
        </div>
        
        <?php include_once ROOT . '/resources/components/tagPicker.php'; ?>
      </form>
      <?php include_once ROOT . '/resources/includes/uploadMobileMessage.php'; ?>
    </div>
  </div>
</section>
<?php include_once ROOT . '/resources/includes/scripts.php'; ?>
</body>
</html>