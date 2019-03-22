<?php
function printCards($type, $directID, $sort, $sort_dir, $limit, $page, $filter, $mode = 'print'){
  require 'resources/filter.php';

  $url = "https://modelsaber.com/files/";
  $dir = "../files/";
  if (isset($_GET['page'])){
    $current_page = filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT);
  } else {
    $current_page = 1;
  }
  if ($page != '') {
    $current_page = $page;
  }
  $offset = (($current_page - 1) * $limit);

  $host        = "host = 127.0.0.1";
  $port        = "port = 5432";
  $dbname      = "dbname = modelsaber";
  $credentials = "user = nginx password=password";
  $db = pg_connect( "$host $port $dbname $credentials" );
  if(!$db) {
    exit;
  }

  if ($directID != "") {
    $sqlQuery = <<<EOF
    SELECT  name,
            author,
            filename,
            image,
            tags,
            hash,
            bsaber,
            uploader,
            approved,
            comments
    FROM models
    WHERE
    id='$directID';
EOF;
    $response = pg_query($db, $sqlQuery);
    if(!$response) {
      echo pg_last_error($db);
      exit;
    } 

    $row = pg_fetch_row($response);
    if ($row[8] != 't') {
      $name = '<span class="down">' . $row[0] . '</span>';
      $author = '<span class="down">' . $row[1] . '</span>';
    } else {
      $name = $row[0];
      $author = $row[1];
    }
    $filename = $row[2];
    $image = $row[3];
    $tags = explode(',', str_replace("\"","", substr($row[4], 1, -1)));
    $hash = $row[5];
    $bsaber = $row[6];
    $uploader = $row[7];
    $comments = $row[9];
    

    echo ("    <div class=\"single-item\">");
    makeSingleCard($name, $author, $type, $url.$type."/".$directID."/".rawurlencode($filename), $dir.$type."/".$directID."/".$image, $name, $tags, $directID, $hash, $bsaber, $comments);
    echo ("    </div>");
  } else {
    $_filters = filter($db, $filter);

    $sqlQuery = <<<EOF
    SELECT  id,
            name,
            author,
            filename,
            image,
            tags,
    count(*) OVER() AS full_count
    FROM models
    WHERE
    approved='true' AND
    type='$type' $_filters
    ORDER BY $sort $sort_dir
    LIMIT $limit
    OFFSET $offset;
EOF;
    $response = pg_query($db, $sqlQuery);
    if(!$response) {
      echo pg_last_error($db);
      exit;
    }
    if ($mode == 'print') {
      echo ("    <div class=\"items\" data-page=\"$current_page\">\n");
    }
    while($row = pg_fetch_row($response)) {
      $id = $row[0];
      $name = $row[1];
      $author = $row[2];
      $filename = $row[3];
      $image = $row[4];
      $tags = explode(',', str_replace("\"","", substr($row[5], 1, -1)));
      if (!isset($total_rows)) $total_rows = $row[6];
      makeCard($name, $author, $type, $url.$type."/".$id."/".rawurlencode($filename), $dir.$type."/".$id."/".$image, $name, $tags, $id);
    }
    $pages = ceil($total_rows / $limit);
    if ($pages > 1) {

      echo ('      <nav id="items-pagination" class="pagination is-centered" role="navigation" aria-label="pagination">');
      if ($current_page <= 1) {
        echo ('        <a class="pagination-previous" disabled><i class="fas fa-angle-double-left"></i></a>');
      } else {
        echo ('        <a onClick="changePage(1)" class="pagination-previous"><i class="fas fa-angle-double-left"></i></a>');
      }
      if ($current_page <= 1) {
        echo ('        <a class="pagination-previous" disabled><i class="fas fa-angle-left"></i></a>');
      } else {
        echo ('        <a onClick="changePage(' . ($current_page - 1) .')" class="pagination-previous"><i class="fas fa-angle-left"></i></a>');
      }
      if ($current_page >= $pages) {
        echo ('        <a class="pagination-next" disabled><i class="fas fa-angle-right"></i></a>');
      } else {
        echo ('        <a onClick="changePage(' . ($current_page + 1) .')" class="pagination-next"><i class="fas fa-angle-right"></i></a>');
      }
      if ($current_page >= $pages) {
        echo ('        <a class="pagination-next" disabled><i class="fas fa-angle-double-right"></i></a>');
      } else {
        echo ('        <a onClick="changePage(' . $pages .')" class="pagination-next"><i class="fas fa-angle-double-right"></i></a>');
      }
      echo ('        <ul class="pagination-list">');
      if ($pages > 5) {
        $min = 1;
        $max = ($pages - 4);
        $start = ($current_page - 2);
        if ($start < $min) $start = $min;
        if ($start > $max) $start = $max;
        $end = ($current_page + 2);
        if ($end < 5) $end = 5;
        if ($end > $pages) $end = $pages;
      } else {
        $start = 1;
        $end = $pages;
      }
      for ($page = $start; $page <= $end; $page++) {
        if ($page == $current_page) {
          echo ('          <li><a class="pagination-link" disabled>' . $page . '</a></li>');
        } else {
          echo ('          <li><a onClick="changePage(' . $page . ')" class="pagination-link">' . $page . '</a></li>');
        }
      }
      echo ('        </ul>');
      echo ('      </nav>');
      if ($mode == 'print') {
        echo ("    </div>\n");
      }
    }
  }
  pg_close($db);
}

function makeCard ($name, $author, $type, $link, $imageUrl, $imageAlt, $tags, $id) {
  $link = str_replace("?","%3F",$link);
  $tagList = "";
  foreach($tags as $tag) {
    if ($tag != ""){
      $tagList .= $tag . ',';
    }
  }
  switch ($type) {
    case "avatar":
      $superType = 'Avatars';
      break;
    case "saber":
      $superType = 'Sabers';
      break;
    case "platform":
      $superType = 'Platforms';
      break;
  }
  $tagList = rtrim($tagList, ',');
  $tagList = strtolower($tagList);
echo <<<EOF
      <div class="item" data-name="${name}" data-author="${author}" data-id="${id}" data-tags="${tagList}">
        <div class="card">
          <div class="card-image">
            <figure class="image is-1by1">
              <img src="${imageUrl}" alt="${imageAlt}">
            </figure>
          </div>
          <div class="card-content-container">
            <div class="card-content">
              <div class="media">
                <div class="media-content">
                  <p class="title is-4">${name}</p>
                  <p class="subtitle is-6">${author}</p>
                </div>
              </div>
              <div class="tags">\n
EOF;
  foreach($tags as $tag) {
    if ($tag != ""){
      echo ("                <span class=\"tag is-rounded\">${tag}</span>\n");
    }
  }
echo <<<EOF
              </div>
            </div>
          </div>
          <div class="card-footer">
            <a href="modsaber://${type}/${link}" class="card-footer-item">Install</a>
            <a href="${link}" class="card-footer-item">Download</a>
            <a href="https://modelsaber.com/${superType}/?id=${id}" class="card-footer-item">Details</a>
          </div>
        </div>
      </div>\n
EOF;
}

function makeSingleCard ($name, $author, $type, $link, $imageUrl, $imageAlt, $tags, $id, $hash, $bsaberName, $comments) {
  $link = str_replace("?","%3F",$link);
  $tagList = "";
  foreach($tags as $tag) {
    if ($tag != ""){
      $tagList .= $tag . ',';
    }
  }
  switch ($type) {
    case "avatar":
      $superType = 'Avatars';
      break;
    case "saber":
      $superType = 'Sabers';
      break;
    case "platform":
      $superType = 'Platforms';
      break;
  }
  $tagList = rtrim($tagList, ',');
  $tagList = strtolower($tagList);
echo <<<EOF
      <div class="itemSingle">
        <div class="image">
          <figure class="image is-1by1">
            <img src="${imageUrl}" alt="${imageAlt}">
          </figure>
        </div>
        <div class="itemInfo">
          <div>
            <h1 class="title is-1">${name}</h1>
            <h3 class="subtitle is-3">${author}</h3>
          </div>
          <div class="itemButtons">
            <a href="modsaber://${type}/${link}" class="button">Install</a>
            <a href="${link}" class="button">Download</a>
            <a onClick="navigator.clipboard.writeText('https://modelsaber.com/${superType}/?id=${id}')" class="button">Copy Link</a>\n
EOF;
  if ($bsaberName != "") {
    echo ("            <a target=\"_blank\" href=\"https://bsaber.com/members/${bsaberName}\" class=\"button\">BeastSaber Profile</a>");
  }
echo <<<EOF
          </div>
          <div>
            <div class="field">
              <label class="label">Hash</label>
              <div class="control">
                <span class="input disabled">${hash}</span>
              </div>
            </div>
            <div class="field">
              <label class="label">ID</label>
              <div class="control">
                <span class="input disabled">${id}</span>
              </div>
            </div>
          </div>
          <label class="label">Tags</label>
          <div class="tags">\n
EOF;
  foreach($tags as $tag) {
    if ($tag != ""){
      echo ("              <span class=\"tag is-rounded\">${tag}</span>\n");
    }
  }
echo <<<EOF
          </div>
          <div>
            <a href="./" class="button"><i class="fas fa-long-arrow-alt-left"></i><span>Back</span></a>
          </div>
        </div>
      </div>\n
EOF;
  if ($comments != "") {
echo <<<EOF
      <div>
        <hr>
        <p>
          $comments
        </p>
      </div>\n
EOF;
  }
}

if (isset($_POST['mode']) && $_POST['mode'] == 'fetch') {
  $type = filter_var($_POST['type'], FILTER_SANITIZE_EMAIL);
  $limit = filter_var($_POST['limit'], FILTER_SANITIZE_NUMBER_INT);
  $page = filter_var($_POST['page'], FILTER_SANITIZE_NUMBER_INT);
  $filter = $_POST['filter'];

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

  printCards($type, '', $sort, $sort_dir, $limit, $page, $filter, 'fetch');
}

?>