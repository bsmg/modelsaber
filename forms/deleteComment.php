<?php
require_once '../resources/includes/constants.php';
if ($currentUser->isLoggedIn()) {
  if (isset($_POST['deleteComment']) && !empty($_POST['deleteComment'])) {
    if ($currentUser->deleteComment($_POST['deleteComment']) == true) {
        
    }
  }
}
header('Location: ' . $_POST['redirect'] . '?id=' . $_POST['modelId']);