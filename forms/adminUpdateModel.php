<?php
require_once '../resources/includes/constants.php';
require_once ROOT . '/model.php';
require_once ROOT . '/webhook.php';
if (isset($_POST['page']) && !empty($_POST['page'])) {
  $page = $_POST['page'];
} else {
  $page = 1;
}
$action = "";
if ($currentUser->isVerified() && $currentUser->isAdmin()) {
  if (isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    $model = new Model();
    $model->readSingle($_POST['id'], 'adminUpdate');
    
    if ($action == 'adminUpdateSingle') {
      $values['variationId'] = $_POST['variationId'];
      $values['discordId'] = $_POST['discordId'];
    }
    
    $values['tags'] = $_POST['tags'];
    $values['description'] = $_POST['description'];
//    $values['name'] = $_POST['name'];
//    $values['author'] = $_POST['author'];
    $values['status'] = $_POST['status'];
    $values['notifMessage'] = $_POST['notifMessage'];
    
    $model->update($values);
  }
}
if ($action == 'adminUpdate') {
  header('Location: ' . $_POST['redirect'] . '?page=' . $page);
} else {
  header('Location: ' . $_POST['redirect'] . '?id=' . $_POST['id']);
}
