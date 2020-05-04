<?php
global $output;
/*global $status;
global $page;
global $mode;
global $modelType;*/

//require_once ROOT . '/model.php';
//  $model = new Model();
if (isset($_POST['mode']) && $_POST['mode'] == 'fetch') {
  $mode = 'fetch';
} else {
  $mode = "print";
}
/*if (!isset($mode) && isset($_POST['mode'])) {
  $mode = $_POST['mode'];
} else {
  $mode = "print";
}*/
if (isset($page)) {
  $current_page = $page;
} else {
  $current_page = 1;
}
/*if(isset($_GET['page']) && !empty($_GET['page'])) {
  $current_page = $_GET['page'];
} else if (isset($page)) {
  $current_page = $page;
} else {
  $current_page = 1;
}*/

  if ($output === true) {
  include_once ROOT . '/resources/templates/printAdminSingleCard.php';
} else if (gettype($output) == 'array') {
  $modelOld = $model;
  /*if (isset($page) && !isset($model->getCurrentPage)){
    $current_page = filter_var($page, FILTER_SANITIZE_NUMBER_INT);
  } else {
    $current_page = 1;
  }*/ ?>
<?php if ($mode == 'fetch'): ?>
<!--<div class="items-admin items">-->
  <?php endif; ?>
  <?php foreach ($output as $model) {
    if ($status == "approved") {
    $positive = "edit";
    $negative = "unapprove";
    $positiveLabel = "Edit";
    $negativeLabel = "Unapprove";
  } else {
    $positive = "accept";
    $negative = "delete";
    $positiveLabel = "Approve";
    $negativeLabel = "Delete";
  }
    include ROOT . '/resources/templates/printAdminCard.php';
  }
  $model = $modelOld;
  ?>
<?php include_once ROOT . '/resources/components/pagination.php'; ?>
  <?php if ($mode !== "fetch"): ?>
<!--</div>-->
<?php endif;
 } else if ($output === "No models were found") { ?>
<span class="is-size-4"><?= $output ?></span>
<?php } else { FishyUtils::getInstance()->error($output); }

?>