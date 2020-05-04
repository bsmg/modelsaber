<?php
global $limit;
    
    FishyUtils::getInstance()->log('Getting Pagination from file with current_page of ' . $current_page);
    /*if (isset($_POST['page']) && !empty($_POST['page'])){
    $current_page = filter_var($_POST['page'], FILTER_SANITIZE_NUMBER_INT);
  } else {
    $current_page = 1;
  }*/
  
    $pages = ceil($output[0]->getTotalRows() / $limit);
    if ($pages > 1): ?>

      <nav id="items-pagination" class="pagination is-centered" role="navigation" aria-label="pagination">
      <?php if ($current_page <= 1): ?>
        <a class="pagination-previous" disabled><i class="fas fa-angle-double-left"></i></a>
      <?php else: ?>
        <a onClick="changePage(1)" class="pagination-previous"><i class="fas fa-angle-double-left"></i></a>
      <?php endif; ?>
      <?php if ($current_page <= 1): ?>
        <a class="pagination-previous" disabled><i class="fas fa-angle-left"></i></a>
      <?php else: ?>
        <a onClick="changePage(<?= $current_page - 1 ?>)" class="pagination-previous"><i class="fas fa-angle-left"></i></a>
      <?php endif; ?>
      <?php if ($current_page >= $pages): ?>
        <a class="pagination-next" disabled><i class="fas fa-angle-right"></i></a>
      <?php else: ?>
        <a onClick="changePage(<?= $current_page + 1 ?>)" class="pagination-next"><i class="fas fa-angle-right"></i></a>
      <?php endif; ?>
      <?php if ($current_page >= $pages): ?>
        <a class="pagination-next" disabled><i class="fas fa-angle-double-right"></i></a>
      <?php else: ?>
        <a onClick="changePage(<?= $pages ?>)" class="pagination-next"><i class="fas fa-angle-double-right"></i></a>
      <?php endif; ?>
      <ul class="pagination-list">
        <?php
      if ($pages > 5) {
        $min = 1;
        $max = ($pages - 4);
        $start = ($current_page - 2);
        if ($start < $min) { $start = $min; }
        if ($start > $max) { $start = $max; }
        $end = ($current_page + 2);
        if ($end < 5) { $end = 5; }
        if ($end > $pages) { $end = $pages; }
      } else {
        $start = 1;
        $end = $pages;
      }
      for ($page = $start; $page <= $end; $page++) {
        if ($page == $current_page): ?>
          <li><a class="pagination-link" disabled><?= $page ?></a></li>
        <?php else: ?>
          <li><a onClick="changePage(<?= $page ?>)" class="pagination-link"><?= $page ?></a></li>
        <?php endif;
      } ?>
      </ul>
      </nav>
<?php
//      if ($mode == 'print') {
//        echo "</div>";
//      }
?>
    <?php elseif ($pages == 1): ?>
<nav id="items-pagination" class="pagination is-centered" role="navigation" aria-label="pagination">
  <a class="pagination-previous" disabled><i class="fas fa-angle-double-left"></i></a>
  <a class="pagination-previous" disabled><i class="fas fa-angle-left"></i></a>
  <a class="pagination-next" disabled><i class="fas fa-angle-right"></i></a>
  <a class="pagination-next" disabled><i class="fas fa-angle-double-right"></i></a>
  <ul class="pagination-list">
    <li><a class="pagination-link" disabled>1</a></li>
  </ul>
</nav>
<?php endif; ?>