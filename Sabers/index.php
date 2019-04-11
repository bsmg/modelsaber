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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta content="text/html; charset=utf-8" http-equiv="content-type">
  <!-- Start Favicon-->
  <link rel="apple-touch-icon" sizes="57x57" href="https://cdn.assistant.moe/favicon/modelsaber/saber/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="https://cdn.assistant.moe/favicon/modelsaber/saber/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="https://cdn.assistant.moe/favicon/modelsaber/saber/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="https://cdn.assistant.moe/favicon/modelsaber/saber/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="https://cdn.assistant.moe/favicon/modelsaber/saber/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="https://cdn.assistant.moe/favicon/modelsaber/saber/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="https://cdn.assistant.moe/favicon/modelsaber/saber/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="https://cdn.assistant.moe/favicon/modelsaber/saber/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="https://cdn.assistant.moe/favicon/modelsaber/saber/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192"  href="https://cdn.assistant.moe/favicon/modelsaber/saber/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="https://cdn.assistant.moe/favicon/modelsaber/saber/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="https://cdn.assistant.moe/favicon/modelsaber/saber/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="https://cdn.assistant.moe/favicon/modelsaber/saber/favicon-16x16.png">
  <link rel="manifest" href="https://cdn.assistant.moe/favicon/modelsaber/saber/manifest.json">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="/favicon/saber/ms-icon-144x144.png">
  <!-- End Favicon -->
<?php
if (isset($_GET['id'])) {
    require '../embed.php';
    getEmbed ("saber", $_GET['id']);
  } else {
echo <<<EOF
  <!-- Start OEmbed --> 
  <meta content="ModelSaber" property="og:site_name">
  <meta content="Saber" property="og:title">
  <meta content="Waifu whackers" property="og:description">
  <meta content="#ebf4f9" name="theme-color">
  <meta content="/resources/saber.png" property="og:image">
  <!-- End OEmbed -->
EOF;
  }
?>
  <title>ModelSaber</title>
  <link href="https://cdn.assistant.moe/css/bulma.css" media="screen" rel="stylesheet">
  <link href="https://cdn.assistant.moe/css/custom.css" rel="stylesheet">
  <link href="https://cdn.assistant.moe/css/light.css" id="light-theme" rel="stylesheet">
  <link href="https://cdn.assistant.moe/css/dark.css" id="dark-theme" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.js"></script>
  <script src="https://cdn.assistant.moe/js/theme.js"></script>
</head>
<body>
<!-- Start Nav Bar -->
<nav class="navbar has-shadow" aria-label="main navigation">
  <div class="container">
    <div class="navbar-brand">
      <a class="navbar-item modelsaber-logo" href="/">
        <i class=icon-modelsaber></i>
      </a>
      <a role="button" class="navbar-burger" data-target="navMenu" aria-label="menu"  aria-expanded="false">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
      </a>
    </div>
    <div class="navbar-menu" id="navMenu">
      <div class="navbar-start">
        <a class="navbar-item upload modal-trigger" data-target="upload" href="/Upload">
          <i class="fas fa-cloud-upload-alt fa-2x"></i>
        </a>
      </div>
      <div class="navbar-end">
<?php
if (!isset($_GET['id'])) {
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
  }
echo <<<EOF
        <a class="navbar-item" onClick="sortDirectionToggle()" id="sortDirection" data-sort="$sort_direction">
          $sort_dir_label
        </a>
        <div class="navbar-item has-dropdown is-hoverable sort">
          <a class="navbar-link" id="currentSort">
            $sort_label
          </a>
          <div class="navbar-dropdown">
            <a class="navbar-item" onClick="sort('name')">
              Name
            </a>
            <a class="navbar-item" onClick="sort('author')">
              Author
            </a>
            <a class="navbar-item" onClick="sort('date')">
              Date
            </a>
          </div>
        </div>
        <div class="navbar-item has-dropdown is-hoverable sort">
          <a class="navbar-link" id="pageLimit">
            $limit Per Page
          </a>
          <div class="navbar-dropdown">
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
EOF;
}
?>
        <a class="navbar-item donate" target="_blank" href="https://bs.assistant.moe/Donate">
          <i class="fab fa-gratipay fa-2x"></i>
        </a>
        <a class="navbar-item settings modal-trigger" href="/api">
          <i class="fas fa-code fa-2x"></i>
        </a>
        <a class="navbar-item settings modal-trigger" href="/Manage">
          <i class="fas fa-cog fa-2x"></i>
        </a>
      </div>
    </div>
  </div>
</nav>
<!-- End Nav Bar -->
<section class="section">
  <div class="container">
<?php
  if (isset($_GET['redirect']) && $_GET['redirect'] == "true") {
echo <<< EOF
    <div class="notification is-warning">
      <i class="fas fa-exclamation-triangle"></i> 
      The URL has changed to <strong>https://modelsaber.com</strong>
    </div>
EOF;
  }
?>
    <!-- Start Tabs -->
    <div class="tabs is-centered is-boxed">
      <ul>
        <li>
          <a href="/Avatars">
            <span class="icon is-small"><i class="fas fa-hat-wizard" aria-hidden="true"></i></span>
            <span>Avatars</span>
          </a>
        </li>
        <li class="is-active">
          <a href="/Sabers">
            <span class="icon is-small"><i class="fas fa-magic" aria-hidden="true"></i></span>
            <span>Sabers</span>
          </a>
        </li>
        <li>
          <a href="/Platforms">
            <span class="icon is-small"><i class="fas fa-torii-gate" aria-hidden="true"></i></span>
            <span>Platforms</span>
          </a>
        </li>
      </ul>
    </div>
    <!-- End Tabs -->
<?php
if (!isset($_GET['id'])) {
echo <<<EOF
    <!-- Start Filter -->
    <div id="filterTitle">
      <h4 class="title is-4">Filters</h4>
      <div class="field has-addons">
        <p class="control">
          <input class="input is-rounded" id="filterInput" form="none" type="text" placeholder="Filter">
        </p>
        <p class="control">
          <button onClick="filterAdd()" form="none" class="button is-success is-rounded">
            <span class="icon is-small">
              <i class="fas fa-plus"></i>
            </span>
          </button>
        </p>
      </div>
    </div>
    <div>
      <div id="filterPool" class="tags">
      </div>
    </div>
    <hr />
    <!-- End Filter -->
EOF;
}
?>
    <!-- Start Items -->
<?php
require '../cards.php';
if (isset($_GET['id'])) {
  $id = $_GET['id'];
} else {
  $id = "";
}
printCards("saber", $id, $sort, $sort_dir, $limit, '', '*');
?>
    <!-- End Items -->
  </div>
</section>
<script src="https://cdn.assistant.moe/js/burger.js"></script>
<script src="/resources/fetcher.js"></script>
<script src="/resources/magnify.js"></script>

</body>
</html>