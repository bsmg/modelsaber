<?php
require_once '../resources/includes/constants.php';
require_once ROOT . '/keyHandler.php';
if ($currentUser->isVerified()) {
  $key = new KeyHandler();
  $key->setUserId($currentUser->getDiscordId());
  
  $canVote = (isset($_POST['canVote'])) ? true : false;
  $canManage = false;
  if ($currentUser->canApprove()) {
    $canManage = (isset($_POST['canManage'])) ? true : false;
  }

  $key->setCanVote($canVote);
  $key->setCanManage($canManage);

  $key->create();
}
header('Location: ' . $_POST['redirect']);
