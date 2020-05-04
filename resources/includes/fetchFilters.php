<?php
require_once 'constants.php';
require_once ROOT . '/model.php';

if (isset($_POST['mode']) && $_POST['mode'] == 'fetch') {
  if (isset($_POST['type'])) {
    $type = filter_var($_POST['type'], FILTER_SANITIZE_EMAIL);
  } else {
    global $type;
  }
  
  if (isset($_POST['modelType'])) {
    $modelType = filter_var($_POST['modelType'], FILTER_SANITIZE_STRING);
  } else {
    global $modelType;
  }
  
  if (isset($_POST['limit'])) {
    $limit = filter_var($_POST['limit'], FILTER_SANITIZE_NUMBER_INT);
  } else {
    global $limit;
  }
  
  if (isset($_POST['page'])) {
    $page = filter_var($_POST['page'], FILTER_SANITIZE_NUMBER_INT);
  } else {
    global $page;
  }
  
  if (isset($_POST['status'])) {
    $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
  } else {
    $status = UNAPPROVED;
  }
  
  $mode = $_POST['mode'];
  if (isset($_POST['filter'])) {
    $filter = $_POST['filter'];
  } else {
    global $filter;
  }
$paths = explode('/', $_SERVER['PHP_SELF']);
$path = $paths[count($paths)-2];
// $isQuest = (strpos(strtolower($_SERVER['PHP_SELF']), '/quest/'))? true : false;
$isQuest = (isset($_GET['quest'])) ? true : false;

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
  if ($type == 'manag') {
//    require_once ROOT . '/model.php';
    $model = new Model();
    $output = $model->readMultiple('fetch', 'Manage');
    require_once ROOT . '/resources/includes/modelAdminCard.php';
  } else if ($type == 'avatar' || $type == 'saber' || $type == 'platform' || $type == 'bloq' || $type == 'trail' || $type == 'sign' || $type == 'misc') {
//    require_once ROOT . '/model.php';
  $model = new Model();
  if ($isQuest) {
    $output = $model->readMultiple('fetch', 'quest');
  } else {
    $output = $model->readMultiple('fetch');
  }
  require_once ROOT . '/resources/includes/modelCard.php';
  } else if (strtolower($type) == 'profil') {
    $model = new Model();
    $output = $model->readMultiple('fetch', 'Profile');
    require_once ROOT . '/resources/includes/modelCard.php';
  }
} else {
  $mode = 'print';
//  require_once ROOT . '/model.php';
$model = new Model();
$useage = (strpos(strtolower($_SERVER['PHP_SELF']), '/manage/') !== FALSE)? 'Manage' : '';
if (isset($_GET['id'])) {
  $output = $model->readSingle($_GET['id'], $useage);
} else if (isset($_GET['variation'])) {
  $output = $model->readMultiple($mode, 'Variation');
} else if (isset($_GET['user'])) {
  $output = $model->readMultiple($mode, 'Profile');
} else {
  $output = $model->readMultiple($mode, $useage);
}
switch ($useage) {
  case 'Manage':
    require_once ROOT . '/resources/includes/modelAdminCard.php';
    break;
  default:
    require_once ROOT . '/resources/includes/modelCard.php';
    break;
}
}   
?>