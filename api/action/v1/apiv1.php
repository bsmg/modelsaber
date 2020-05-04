<?php
require_once '../../../helper.php';
require_once '../../../dbConnection.php';
require_once '../../../keyHandler.php';
function apiCall() {
  return 'the action api is currently under construction!';
  dbConnection::getInstance()->start();
  $key = '';
  $action = '';
  $keyHandler = new KeyHandler();
  
  if (isset($_GET['key'])) {
    $key = $_GET['key'];
  }
  if (isset($_GET['action'])) {
    $action = $_GET['action'];
  }
  
  $keyHandler->read($key);
  if ($keyHandler->getKey() !== $key) {
    return 'key doesn\'t exist!';
  }
  
  if ($action == 'vote') {
    if (!$keyHandler->getCanVote()) {
      return 'You don\'t have access to vote with this key!';
    }
    
    if (isset($_GET['type'])) {
      $voteType = $_GET['type'];
    }
    
  } else if ($action == 'manage') {
    if (!$keyHandler->getCanManage()) {
      return 'You don\'t have access to manage approvals with this key!';
    }
    
    $manageMessage = '';
    
    if (isset($_GET['state'])) {
      $manageState = $_GET['state'];
    }
    if (isset($_GET['message'])) {
      $manageMessage = $_GET['message'];
    }
  }
  
  dbConnection::getInstance()->close();
}

