<?php
require_once '../resources/includes/constants.php';
require_once ROOT . '/model.php';
if (isset($_POST['action']) && !empty($_POST['action'])) {
  $model = new Model();
  $model->readSingle($_POST['id'], 'update');
  if ($model->isAuthor($currentUser->getDiscordId())) {
    $values['tags'] = $_POST['tags'];
    $values['description'] = $_POST['description'];
    if (!$_FILES['file']['error']) {
      $values['file'] = $_FILES['file'];
    }
    if (!$_FILES['image']['error']) {
      $values['image'] = $_FILES['image'];
    }
    if (!$_FILES['unityproject']['error']) {
      $values['unityproject'] = $_FILES['unityproject'];
    }
    $model->update($values);
  }
}
if (isset($_POST['redirect']) && isset($_POST['platform']) && isset($_POST['id'])) {
  header('Location: ' . $_POST['redirect'] . '?id=' . $_POST['id'] . '&' . $_POST['platform']);
} else {
  header('Location: ' . WEBROOT);
}
