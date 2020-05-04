<?php
require_once '../resources/includes/constants.php';
require_once ROOT . '/user.php';
if ($currentUser->isVerified() && $currentUser->isAdmin()) {
  if (isset($_POST['action']) && !empty($_POST['action'])) {
    $user = new User();
    $user->read($_POST['id']);
    
    $values['username'] = $_POST['username'];
    $values['discriminator'] = $_POST['discriminator'];
    $values['bsaber'] = $_POST['bsaber'];
    $values['roles'] = $_POST['roles'];
    $values['description'] = $_POST['description'];
    $values['admin'] = true;
    
    $user->update($values);
  }
}
header('Location: ' . $_POST['redirect'] . '?id=' . $_POST['id']);
