<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../resources/webhooks.php';

$host        = "host = 127.0.0.1";
$port        = "port = 5432";
$dbname      = "dbname = modelsaber";
$credentials = "user = nginx password=password";
$db = pg_connect( "$host $port $dbname $credentials" );
if(!$db) {
  exit;
}

if(isset($_POST['mode'])){
  $mode = $_POST['mode'];
  $fileID = $_POST['id'];
  $fileType = $_POST['type'];

  function delTree($dir) {
    $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
      (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
  } 

  switch ($mode) {
    case "accept":
      edit($db, 'true', 'yes');
      break;
    case "delete":
      delTree ('../files/'.$fileType.'/'.$fileID);
      pg_query($db, "DELETE FROM models WHERE id = $fileID");
      break;
    case "unapprove":
      edit($db, 'false');
      break;
    case "edit":
      edit($db, 'true');
      break;
  }
  header("Location: /Manage");
} 

function edit($db, $approval, $approving = '') {
  $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
  $tags = $_POST['tags'];
  $tags = pg_escape_literal('{'.$tags.'}');
  $name = pg_escape_literal($_POST['name']);
  $author = pg_escape_literal($_POST['author']);
  $image = $_POST['image'];
  $bsaber = pg_escape_literal($_POST['bsaber']);
  $link = $_POST['link'];
  $comments = pg_escape_literal(strip_tags($_POST['comments'], '<b><br><hr><strong><em>'));

  $sqlQuery = <<<EOF
  UPDATE models SET
    approved = $approval,
    name = $name,
    author = $author,
    tags = $tags,
    bsaber = $bsaber,
    comments = $comments
  WHERE
  id='$id';
EOF;
echo ($sqlQuery);

  $response = pg_query($db, $sqlQuery);
  if(!$response) {
    echo pg_last_error($db);
    exit;
  }
  if ($approving == 'yes') {
    $type = $_POST['type'];
    $tags = str_replace(',', ', ', $_POST['tags']);
    postWebhooks($type, $name, $author, $image, $id, $tags, $link, $comments);
  }
}

function printManager($type, $approveState, $db){
  $dir = "../files/";
  $url = "modelsaber.com/files/";

  $sqlQuery = <<<EOF
  SELECT  id,
          name,
          author,
          filename,
          image,
          tags,
          hash,
          bsaber,
          uploader,
          comments
  FROM models
  WHERE
  approved='$approveState' AND
  type='$type';
EOF;
  $response = pg_query($db, $sqlQuery);
  if(!$response) {
    echo pg_last_error($db);
    exit;
  } 

  while($row = pg_fetch_row($response)) {
    $id = $row[0];
    $name = $row[1];
    $author = $row[2];
    $filename = $row[3];
    $image = $row[4];
    $tags = explode(',', str_replace("\"","", substr($row[5], 1, -1)));
    $hash = $row[6];
    $bsaber = $row[7];
    $uploader = $row[8];
    $comments = $row[9];
    makeCard($name, $author, $type, $url.$type."/".$id."/".$filename, $image, $dir.$type."/".$id."/".$image, $name, $tags, $id, $hash, $bsaber, $approveState, $comments);
  }
}

function makeCard ($name, $author, $type, $link, $image, $imageUrl, $imageAlt, $tags, $id, $hash, $bsaber, $approveState, $comments) {
  $link = str_replace("?","%3F",$link);
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

  if ($approveState == "true") {
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
  
  $tagList = "";
  foreach($tags as $tag) {
    if ($tag != ""){
      $tagList .= $tag . ',';
    }
  }
  $tagList = rtrim($tagList, ',');
echo <<<EOF
      <div class="item">
        <div class="card">
          <div class="card-image">
            <figure class="image is-1by1">
              <img src="${imageUrl}" alt="${imageAlt}">
            </figure>
          </div>
          <div class="card-content-admin">
            <div class="media">
              <div class="media-content">
                <div class="field">
                  <label class="label">Name</label>
                  <div class="control">
                    <input class="input" type="text" name="name" value="${name}" form="${positive}-${id}">
                  </div>
                </div>
                <div class="field">
                  <label class="label">Author</label>
                  <div class="control">
                    <input class="input" type="text" name="author" value="${author}" form="${positive}-${id}">
                  </div>
                </div>
                <div class="field">
                  <label class="label">Tags</label>
                  <div class="control">
                    <input class="input" type="text" name="tags" value="${tagList}" form="${positive}-${id}">
                  </div>
                </div>
                <div class="field">
                  <label class="label">BeastSaber Username</label>
                  <div class="control">
                    <input class="input" type="text" name="bsaber" value="${bsaber}" form="${positive}-${id}">
                  </div>
                </div>
                <div class="field">
                  <label class="label">Hash</label>
                  <div class="control">
                    <span class="input disabled">${hash}</span>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Comments</label>
                  <div class="control">
                    <textarea class="textarea" type="text" name="comments" form="${positive}-${id}">${comments}</textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <form action="index.php" method="POST" id="${negative}-${id}">
            <input type="hidden" name="mode" value="${negative}">
            <input type="hidden" name="type" value="${type}">
            <input type="hidden" name="id" value="${id}">
            <input type="hidden" name="name" value="${name}">
            <input type="hidden" name="author" value="${author}">
            <input type="hidden" name="tags" value="${tagList}">
            <input type="hidden" name="bsaber" value="${bsaber}">
            <input type="hidden" name="comments" value="${comments}">
          </form>
          <form action="index.php" method="POST" id="${positive}-${id}">
            <input type="hidden" name="mode" value="${positive}">
            <input type="hidden" name="type" value="${type}">
            <input type="hidden" name="oldName" value="${name}">
            <input type="hidden" name="id" value="${id}">
            <input type="hidden" name="image" value="${image}">
            <input type="hidden" name="link" value="${link}">
          </form>
          <div class="card-footer">
            <a href="modsaber://${type}/https://${link}" class="card-footer-item">Install</a>
            <a href="https://${link}" class="card-footer-item">Download</a>
          </div>
          <div class="card-footer">
            <a href="https://modelsaber.com/${superType}/?id=${id}" class="card-footer-item">View</a>
            <a onclick="document.getElementById('${positive}-${id}').submit()" class="card-footer-item">${positiveLabel}</a>
            <a onclick="document.getElementById('${negative}-${id}').submit()" class="card-footer-item">${negativeLabel}</a>
          </div>
        </div>
      </div>
EOF;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta content="text/html; charset=utf-8" http-equiv="content-type">
  <title>ModelSaber</title>
  <link href="https://cdn.assistant.moe/css/bulma.css" media="screen" rel="stylesheet">
  <link href="https://cdn.assistant.moe/css/custom.css" rel="stylesheet">
  <link href="https://cdn.assistant.moe/css/light.css" id="light-theme" rel="stylesheet">
  <link href="https://cdn.assistant.moe/css/dark.css" id="dark-theme" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
      </div>
    </div>
  </div>
</nav>
<!-- End Nav Bar -->
<section class="section">
  <div class="container">
    <h3 class="is-3 title">Avatars</h3>
    <div class="items-admin">
<?php
printManager("avatar", 'false', $db);
?>
    </div>
    <h3 class="is-3 title">Sabers</h3>
    <div class="items-admin">
<?php
printManager("saber", 'false', $db);
?>
    </div>
    <h3 class="is-3 title">Platforms</h3>
    <div class="items-admin">
<?php
printManager("platform", 'false', $db);
?>
    </div>
    <h1 class="is-1 title">Approved</h1>
    <hr />
    <h3 class="is-3 title">Avatars</h3>
    <div class="items-admin">
<?php
printManager("avatar", 'true', $db);
?>
    </div>
    <h3 class="is-3 title">Sabers</h3>
    <div class="items-admin">
<?php
printManager("saber", 'true', $db);
?>
    </div>
    <h3 class="is-3 title">Platforms</h3>
    <div class="items-admin">
<?php
printManager("platform", 'true', $db);
pg_close($db);
?>
    </div>
  </div>
</section>
<script src="https://cdn.assistant.moe/js/burger.js"></script>
</body>
</html>