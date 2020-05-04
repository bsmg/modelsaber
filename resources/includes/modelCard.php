<?php
//require_once ROOT . '/model.php';
//$model = new Model();
//$output = $model->read();
//global $mode;
//global $page;
//global $output;
if (isset($_POST['mode']) && $_POST['mode'] == 'fetch') {
  $mode = 'fetch';
} else {
  $mode = "print";
}
//!isset($current_page)
if (isset($page)) {
  $current_page = $page;
} else {
  $current_page = 1;
}
/*if (isset($_POST['page']) && !empty($_POST['page'])){
    $current_page = filter_var($_POST['page'], FILTER_SANITIZE_NUMBER_INT);
  } else {
    $current_page = 1;
  }*/
?>
<?php if ($output === true): ?>
  <?php include ROOT . '/resources/templates/printSingleCard.php'; ?>
<?php elseif (gettype($output) == 'array'):
  $modelOld = $model;
  /*if (isset($_POST['page']) && !isset($current_page)){
    $current_page = filter_var($_POST['page'], FILTER_SANITIZE_NUMBER_INT);
  } else {
    $current_page = 1;
  }*/ ?>
  <?php if (isset($_GET['variation']) && !empty($_GET['variation'])): ?>
    <h2 class="title is-2"><?= $output[0]->getVariationTitle(); ?></h2>
  <?php endif; ?>
  <?php if ($mode == "print"): ?>
    <div class="items" data-page="<?= $current_page ?>">
  <?php endif; ?>
  <?php foreach ($output as $model): ?>
    <?php include ROOT . '/resources/templates/printCard.php'; ?>
  <?php endforeach; ?>
  <?php $model = $modelOld; ?>
  <?php include_once ROOT . '/resources/components/pagination.php'; ?>
  <?php if ($mode == "print"): ?>
    </div>
    <?php //$output[0]->getPagination(); ?>
  <?php else: ?>
    <?php //include_once ROOT . '/resources/components/pagination.php'; ?>
    <?php //$output[0]->getPagination(); ?>
  <?php endif; ?>

<?php elseif ($output === "No models were found"): ?>
  <?php FishyUtils::getInstance()->log('No models were found'); ?>
  <span class="is-size-4"><?= $output ?></span>
<?php else: ?>
  <?php FishyUtils::getInstance()->Error($output); ?>
<?php endif; ?>
