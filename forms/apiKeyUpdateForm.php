<?php
require_once '../resources/includes/constants.php';
require_once ROOT . '/keyHandler.php';
if ($currentUser->isVerified()) {
  $key = new KeyHandler();
  $key->read($_POST['id']);
  
  $canVote = (isset($_POST['canVote'])) ? true : false;
  $canManage = false;
  $regenerate = false;
  
  if ($currentUser->canApprove()) {
    $canManage = (isset($_POST['canManage'])) ? true : false;
  }
  
  if (isset($_POST['type']) && $_POST['type'] == 'regenerate') {
    $regenerate = true;
  }
  
  $key->setCanVote($canVote);
  $key->setCanManage($canManage);
  
  $key->update($regenerate);
}
header('Location: ' . $_POST['redirect']);
