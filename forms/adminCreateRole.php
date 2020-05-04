<?php
require_once '../resources/includes/constants.php';
require_once ROOT . '/user.php';
require_once ROOT . '/role.php';
if ($currentUser->isVerified() && $currentUser->isAdmin()) {
  if (isset($_POST['action']) && !empty($_POST['action'])) {
    $role = new Role();
    $role->setId($_POST['addRole']);
    $role->create();
  }
}
header('Location: ' . $_POST['redirect'] . '?user=' . $_POST['id']);
