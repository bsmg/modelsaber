<?php
require_once '../../resources/includes/constants.php';
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
<?php require_once ROOT . '/head.php'; ?>
  <script src="<?= WEBROOT ?>/Upload/1-simple-upload.js"></script>
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
        <li>
          <a href="<?= WEBROOT ?>/Upload?<?= getDevicePlatform() ?>">Simple upload</a>
        </li>
        <li class="is-active">
          <a href="<?= WEBROOT ?>/Upload/Advanced?<?= getDevicePlatform() ?>">Advanced upload</a>
        </li>
      </ul>
    </div>
    <!-- End Tabs -->
    <div class="content">
      <p>If you need help with advanced uploads read the <a href="../Guide" target="_blank">guide here</a>.</p>
      <div>
        <h4 class="title is-4">Guidelines:</h4>
        <ul>
          <li><strong>General:</strong></li>
          <ul>
            <li><strong>NEVER</strong> rip assets or upload a model without the original author's consent. We do <strong>NOT</strong> condone piracy in any way or form!</li>
            <li>The file must be in <kbd>.zip</kbd> format.</li>
          </ul>
          <li><strong>Images:</strong></li>
          <ul>
            <li><strong>MUST</strong> have a <strong>1x1 aspect ratio</strong>.</li>
            <li>Must be <strong>tasteful</strong> and <strong>appropiate</strong>.</li>
            <li><strong>MUST</strong> have the filename <kbd>image</kbd>.</li>
            <li>Images must be <b>at least</b> <?= MINIMAGESIZE; ?> by <?= MINIMAGESIZE; ?> pixels.</li>
          </ul>
        </ul>
      </div>
      <hr />
      <form class="is-relative" action="../upload.php" method="POST" id="uploadForm" enctype="multipart/form-data">
        <input type="hidden" name="formType" value="advanced">
        <div class="field">
          <div class="control">
            <label class="checkbox">
              <input type="checkbox" name="isVariation" onchange='document.getElementById("variationName").disabled = !this.checked;'>
              Variation List <?php info("If this is a variation list, check it.") ?>
            </label>
          </div>
        </div>
        
        <label class="label is-medium">Variation List Name</label>
        <div class="field flex">
          <div class="control">
            <input class="input" name="variationName" form="uploadForm" id="variationName" type="text" disabled placeholder="Unamed variation list">
          </div>
        </div>
        
        <label class="label is-medium">Files <?php info("Supports uploading mutiple files at a time.") ?></label>
        <div id="uploader" class="field flex has-addons file">
          <div class="control">
            <label class="file-label">
              <input id="file-uploader" class="file-input" type="file" accept=".zip" name="file[]" form="uploadForm" required multiple>
              <span class="file-cta">
                <span class="file-icon">
                  <i class="fas fa-upload"></i>
                </span>
                <span class="file-label">
                  Select Files
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
      </form>
      <?php include_once ROOT . '/resources/includes/uploadMobileMessage.php'; ?>
    </div>
  </div>
</section>
<?php include_once ROOT . '/resources/includes/scripts.php'; ?>
</body>
</html>