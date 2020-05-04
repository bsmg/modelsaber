<?php
require_once '../resources/includes/constants.php';
require_once ROOT . '/user.php';
if (isset($_POST['id']) && !empty($_POST['id'])) {
  $id = $_POST['id'];
} else {
  header('Location: ' . WEBROOT);
}
if ($currentUser->isVerified() && $currentUser->getDiscordId() == $id) {
  if (isset($_POST['action']) && !empty($_POST['action'])) {
    $user = new User();
    $user->read($id);
    
    $values['bsaber'] = $_POST['bsaber'];
    $values['description'] = $_POST['description'];
    
    $user->update($values);
  }
}
header('Location: ' . $_POST['redirect'] . '?user=' . $_POST['id']);
