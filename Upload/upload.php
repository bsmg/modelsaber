<?php
require_once '../resources/includes/constants.php';
require_once ROOT . '/model.php';
require_once ROOT . '/discordOAuth.php';
if (!$currentUser->isVerified()) {
  HTTPError(403);
}
if (!$currentUser->canUpload()) {
  HTTPError(403);
}

$complexity = $_POST['formType'];

if ($complexity == 'advanced') {
  $variationTitle = $_POST['variationName'];
}

$url = WEBROOT . '/';

$time = time();

$variationId = $time;
$id = $time;
$files = reArrayFiles($_FILES['file']);
// $images = reArrayFiles($_FILES['image']);
$image = $_FILES['image'];

foreach ($files as $key => $file) {
  $models[$key] = new file($file, $complexity, $time);
  if ($models[$key]->create() === false) {
    unset($models[$key]);
    continue;
  }

  if ($complexity == 'advanced') {
    $models[$key]->setVariationTitle($variationTitle);
  }

  if ($complexity == 'simple') {
    $models[$key]->setImage($image);
  }

  // foreach ($images as $img => $image) {
  //   var_dump(pathinfo($image['name'], PATHINFO_FILENAME) . ' ' . pathinfo($file['name'], PATHINFO_FILENAME) . '<br>');
  //   if (pathinfo($image['name'], PATHINFO_FILENAME) == pathinfo($file['name'], PATHINFO_FILENAME)) {
  //     $models[$key]->setImage($image);
  //   }
  // }

  $time++;
}

$isMultiple = false;
if (count($models) >= 2) {
  $isMultiple = true;
}

if ($isMultiple) {
  $extensions = array_map('mapModelType', $models);
  if (count(array_unique($extensions)) != 1) {
    failed($url, 'File extensions didn\' match');
    die();
  }
  unset($extensions);
} else {
  $variationId = NULL;
}

if ($isMultiple) {
  $hashes = array_map('mapModelHash', $models);
  $uniqueHashes = array_unique($hashes);
  if (count($uniqueHashes) !== count($models)) {
  failed($url, 'Models hash doesn\'t match');
  die();
  }
}

foreach ($models as $model) {
  $tempModel = new Model();
  $tempValues = $model->getModel();

  $type = $tempModel->create($tempValues, $variationId);
  $platform = $tempValues['upload']['platform'];
  
  unset($tempModel);
  unset($tempValues);
}

if (!empty($variationId)) {
  $id = $variationId;
}
if (!empty($type)) {
  success($url, $type, $id, $platform);
} else {
  failed($url, 'An unexpected error occured');
  die();
}

function reArrayFiles(&$file_post) {

  $file_ary = array();
  $file_count = count($file_post['name']);
  $file_keys = array_keys($file_post);

  for ($i = 0; $i < $file_count; $i++) {
    foreach ($file_keys as $key) {
      $file_ary[$i][$key] = $file_post[$key][$i];
    }
  }

  return $file_ary;
}

/**
 * Success
 * 
 * Shows a successful upload page after uploading files.
 * 
 * @param string $url The redirect url.
 * @param string $type The model type of the upload.
 * @param string $id The id of the uploaded model, why is this a string? because php's integer limit is 32 bits.
 */
function success($url, $type, $id, $platform) {
  $superType = getSuperType($type);

  include ROOT . '/resources/templates/uploadSuccess.php';
}

function mapModel($item) {
  return $item->getModel();
}
function mapModelType($item) {
  return $item->getModel()['model']['type'];
}
function mapModelHash($item) {
  return $item->getModel()['model']['hash'];
}
?>