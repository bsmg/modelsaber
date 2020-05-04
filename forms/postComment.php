<?php
require_once '../resources/includes/constants.php';
if ($currentUser->isLoggedIn()) {
  if (isset($_POST['postComment']) && $_POST['postComment'] == "true") {
    if ($currentUser->makeComment($_POST['modelId'], $_POST['commentMessage']) == true) {
        
    }
  }
}
header('Location: ' . $_POST['redirect'] . '?id=' . $_POST['modelId']);