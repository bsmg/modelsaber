<?php
require_once '../resources/includes/constants.php';
if ($currentUser->isLoggedIn()) {
  if (isset($_POST['TOSAccept'])) {
    if (isset($_POST['TOSAction']) && $_POST['TOSAction'] == "true") {
      $currentUser->acceptTOS();
    }
  }
}
header('Location: ' . WEBROOT . '/');