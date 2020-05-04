<?php
switch ($sort_direction) {
  case 'Ascending':
    $sort_dir_label = '<i class="fas fa-arrow-up"></i>';
    break;
  case 'Descending':
    $sort_dir_label = '<i class="fas fa-arrow-down"></i>';
    break;
}
switch ($sort) {
  case 'name':
    $sort_label = 'Name';
    break;
  case 'author':
    $sort_label = 'Author';
    break;
  case 'id':
    $sort_label = 'Date';
    break;
  case 'votes':
    $sort_label = 'Votes';
    break;
}
?>
<?php if (!isset($_GET['id'])) : ?>
  <a class="navbar-item navbar-last" onClick="sortDirectionToggle()" id="sortDirection" data-sort="<?= $sort_direction ?>">
    <?= $sort_dir_label ?>
  </a>
  <div class="navbar-item navbar-last has-dropdown is-hoverable sort mobile-toggle" data-target="sort-dropdown">
    <a class="navbar-link" id="currentSort">
      <?= $sort_label ?>
    </a>
    <div id="sort-dropdown" class="navbar-dropdown">
      <a class="navbar-item" onClick="sort('name')">
        Name
      </a>
      <a class="navbar-item" onClick="sort('author')">
        Author
      </a>
      <a class="navbar-item" onClick="sort('date')">
        Date
      </a>
      <a class="navbar-item" onClick="sort('votes')">
        Votes
      </a>
    </div>
  </div>
  <div class="navbar-item navbar-last has-dropdown is-hoverable sort mobile-toggle" data-target="page-limit-dropdown">
    <a class="navbar-link" id="pageLimit">
      <?= $limit ?> Per Page
    </a>
    <div id="page-limit-dropdown" class="navbar-dropdown">
      <a class="navbar-item" onClick="pageLimitSet(12)">
        12 Items
      </a>
      <a class="navbar-item" onClick="pageLimitSet(24)">
        24 Items
      </a>
      <a class="navbar-item" onClick="pageLimitSet(36)">
        36 Items
      </a>
      <a class="navbar-item" onClick="pageLimitSet(48)">
        48 Items
      </a>
      <a class="navbar-item" onClick="pageLimitSet(60)">
        60 Items
      </a>
      <a class="navbar-item" onClick="pageLimitSet(72)">
        72 Items
      </a>
      <a class="navbar-item" onClick="pageLimitSet(84)">
        84 Items
      </a>
      <a class="navbar-item" onClick="pageLimitSet(96)">
        96 Items
      </a>
    </div>
  </div>
<?php endif; ?>