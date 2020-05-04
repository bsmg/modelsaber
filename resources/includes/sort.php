<?php
if (isset($_COOKIE['sort'])){
  switch ($_COOKIE['sort']) {
    case 'Name':
      $sort = 'name';
      break;
    case 'Author':
      $sort = 'author';
      break;
    case 'Date':
      $sort = 'id';
      break;
    case 'Votes':
      $sort = 'votes';
      break;
    default:
      $sort = 'id';
      break;
  }
} else {
  $sort = 'id';
}
if (isset($_COOKIE['sort_dir'])){
  $sort_direction = $_COOKIE['sort_dir'];
  switch ($_COOKIE['sort_dir']) {
    case 'Ascending':
      $sort_dir = 'ASC';
      break;
    case 'Descending':
      $sort_dir = 'DESC';
      break;
    default:
      $sort_dir = 'DESC';
      break;
  }
} else {
  $sort_dir = 'DESC';
  $sort_direction = 'Descending';
}
if (isset($_COOKIE['limit'])){
  $limit = filter_var($_COOKIE['limit'], FILTER_SANITIZE_NUMBER_INT);
} else {
  $limit = 24;
}
/*if (isset($_POST['status'])) {
    $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
  } else {
    $status = 'unapproved';
  }*/
if (isset($_COOKIE['status'])) {
    $status = $_COOKIE['status'];
  } else if (isset($_POST['status'])) {
    $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
    FishyUtils::getInstance()->log('not empty');
  } else {
    $status = UNAPPROVED;
    FishyUtils::getInstance()->log('empty');
  }
if (isset($_POST['mode']) && $_POST['mode'] == 'fetch') {
  if (isset($_POST['type'])) {
    $type = filter_var($_POST['type'], FILTER_SANITIZE_EMAIL);
  }
  
  if (isset($_POST['modelType'])) {
    $modelType = filter_var($_POST['modelType'], FILTER_SANITIZE_STRING);
  } else {
    $modelType = 'all';
  }
  
  if (isset($_POST['limit'])) {
    $limit = filter_var($_POST['limit'], FILTER_SANITIZE_NUMBER_INT);
  }
  
  if (isset($_POST['page'])) {
    $page = filter_var($_POST['page'], FILTER_SANITIZE_NUMBER_INT);
  }
  /*if (isset($_COOKIE['status'])) {
    $status = $_COOKIE['status'];
  } else if (isset($_POST['status'])) {
    $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
    FishyUtils::getInstance()->log('not empty');
  } else {
    $status = 'unapproved';
    FishyUtils::getInstance()->log('empty');
  }*/
  
  if (isset($_POST['filter'])) {
    $filter = $_POST['filter'];
  } else {
    $filter = '';
  }
  

  if (isset($_POST['sort'])){
    switch ($_POST['sort']) {
      case 'Name':
        $sort = 'name';
        break;
      case 'Author':
        $sort = 'author';
        break;
      case 'Date':
        $sort = 'id';
        break;
      case 'Votes':
        $sort = 'votes';
        break;
      default:
        $sort = 'id';
        break;
    }
  } else {
    $sort = 'id';
  }
  if (isset($_POST['sort_dir'])){
    switch ($_POST['sort_dir']) {
      case 'Ascending':
        $sort_dir = 'ASC';
        break;
      case 'Descending':
        $sort_dir = 'DESC';
        break;
      default:
        $sort_dir = 'DESC';
        break;
    }
  } else {
    $sort_dir = 'DESC';
  }
}
?>