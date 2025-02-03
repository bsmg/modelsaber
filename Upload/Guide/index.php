<?php
require_once '../../resources/includes/constants.php';
if (!$currentUser->isVerified()) {
  HTTPError(403);
}
$summary = [
    'files',
    'settings.json'
]
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once ROOT . '/head.php'; ?>
<!--Start OEmbed--> 
  <meta content="ModelSaber" property="og:site_name">
  <meta content="Upload Guide" property="og:title">
  <meta content="Learn how to upload advanced models" property="og:description">
  <meta content="#ebf4f9" name="theme-color">
  <meta content="/resources/upload.png" property="og:image">
<!--End OEmbed--> 
</head>
<body>
<?php include_once ROOT . '/resources/includes/menu.php'; ?>
<section class="section">
  <div class="container">
    <h2 class="title is-2 has-text-centered">Advanced Upload Guide</h2>
    <hr>
    <div class="content">
      <h3 class="subtitle is-4">Guide Summary</h3>
    <ul>
      <?php foreach ($summary as $id): ?>
      <li><a href='#<?= $id; ?>'><?= ucfirst($id); ?></a></li>
      <?php endforeach; ?>
    </ul>
    </div>
    
    <h4 id="files" class="subtitle is-5">Files</h4>
    <div class="content">
      <p>When uploading a model in advanced mode you have way more options on what to do,<br>
      to allow for this though, we had to change the format of uploads.<br>
      In this section we will explain the file structure of the uploaded .zip file.<br>
      The example file used on this page can by downloaded <a href="download.php" target="_blank">by clicking on this link</a>.<br>
      <sub>note: anything inside &lt; and &gt; should be replaced with the apropriate value.</sub></p>
      <ul>
        <li>
          &lt;name&gt;.zip
          <ul>
            <li>&lt;name&gt;.&lt;model type&gt;</li>
            <li>&lt;name&gt;.&lt;png, jpg, or gif&gt;</li>
            <li>settings.json</li>
            <li>&lt;name&gt;.unitypackage <sup>optional</sup></li>
          </ul>
        </li>
      </ul>
    </div>
    
    <h4 id="settings.json" class="subtitle is-5">settings.json</h4>
    <div class="content">
      <p>The settings.json file is used to specify all fields of the model, both the ones for simple uploads and new ones.<br>
      <sub>note: sub-fields of an optional field are also optional.</sub></p>
      <p class="is-marginless">Optional Fields</p>
      <ul>
        <li>license: source</li>
        <li>license: link</li>
        <li>model: tags</li>
        <li>model: description</li>
        <li>model: dependencies</li>
        <li>embed</li>
      </ul>
    </div>
    <pre><code class="language-json"><?php
ini_set("allow_url_fopen", 1);
echo json_encode(json_decode(file_get_contents('exampleModel/settings.json'), true), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?></code></pre>
    
    
  </div>
</section>
<?php include_once ROOT . '/resources/includes/scripts.php'; ?>
  <script src="<?= WEBROOT ?>/resources/js/prism.js"></script>
</body>
</html>