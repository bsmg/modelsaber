<?php
require_once '../resources/includes/constants.php';
require_once ROOT . '/keyHandler.php';
if ($currentUser->isVerified()) {
  $key = new KeyHandler();
  $key->read($_POST['id']);

  $key->delete();
}
header('Location: ' . $_POST['redirect']);
