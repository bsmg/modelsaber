<?php
$host        = "host = 127.0.0.1";
$port        = "port = 5432";
$dbname      = "dbname = modelsaber";
$credentials = "user = nginx password=password";
$db = pg_connect( "$host $port $dbname $credentials" );
if(!$db) {
  exit;
}

$url = 'https://modelsaber.com/';

$getInfo = $_POST;
$time = time();
$bsaber = pg_escape_literal($_POST['bsaber']);
$comments = pg_escape_literal(strip_tags($_POST['comments'], '<b><br><hr><strong><em>'));

$file = $_FILES['file']['tmp_name'];
$image = $_FILES['image']['tmp_name'];
$imageName = 'image.'.pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
$tags = "";
foreach($getInfo['tags'] as $tag) {
  if ($tag != ""){
    $tags .= $tag . ',';
  }
}
$tags = pg_escape_literal('{'.substr($tags, 0, -1).'}');

exec("python3 getInfo.py $file", $info);
$type = $info[0];
$name = $info[1];
$author = $info[2];
$hash = $info[3];

$dir = '../files/'.$type.'/'.$time.'/';

$imageSize = getimagesize($image);
$width = $imageSize[0];
$height = $imageSize[1];

switch ($type) {
  case "avatar":
    break;
  case "saber":
    break;
  case "platform":
    break;
  default:
    failed($url, 'Upload format is invalid.');
    die();
}

if ($width != $height) {
  failed($url, 'Image is not 1:1 aspect ratio.');
  die();
}

switch (pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION)) {
  case "jpg":
    break;
  case "png":
    break;
  case "gif":
    break;
  default:
    failed($url, 'Image format is invalid.');
    die();
}

if (!isset($hash)) {
  failed($url, '');
  die();
}

mkdir($dir);

switch ($type) {
  case "avatar":
    $ext = 'avatar';
    break;
  case "saber":
    $ext = 'saber';
    break;
  case "platform":
    $ext = 'plat';
    break;
  default:
    die();
}

$fileName = str_replace("/","_",$name).'.'.$ext;

move_uploaded_file($file, $dir.$fileName);
move_uploaded_file($image, $dir.$imageName);

$hash = pg_escape_literal($hash);
$type = pg_escape_literal($type);
$name = pg_escape_literal($name);
$author = pg_escape_literal($author);
$fileName = pg_escape_literal($fileName);
$imageName = pg_escape_literal($imageName);

$sqlQuery = <<<EOF
INSERT INTO models (
  id,
  type,
  name,
  author,
  filename,
  image,
  tags,
  hash,
  bsaber,
  comments)
VALUES (
  $time,
  $type,
  $name,
  $author,
  $fileName,
  $imageName,
  $tags,
  $hash,
  $bsaber,
  $comments)
EOF;
$response = pg_query($db, $sqlQuery);
if(!$response) {
  echo pg_last_error($db);
  exit;
} 
pg_close($db);

$thumgnail = str_replace("image","thumbnail",$imageName);
$oldFile = $dir.$imageName;
$newFile = $dir.$thumgnail;
switch ($imageName) {
  case "image.png":
    shell_exec ( "convert $oldFile -resize 64x64 $newFile");
    break;
  case "image.jpg":
    shell_exec ( "convert $oldFile -resize 64x64 $newFile");
    break;
  case "image.gif":
    shell_exec ( "convert $oldFile -layers Coalesce -resize 64x64 -layers Optimize $newFile");
    break;
}

success($url, $type, $time);

function failed($url, $message) {
echo <<<EOF
<!DOCTYPE html>
<html lang="en">
<head>
  <meta content="text/html; charset=utf-8" http-equiv="content-type">
  <!-- Start Favicon-->
  <link rel="apple-touch-icon" sizes="57x57" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192"  href="https://cdn.assistant.moe/favicon/modelsaber/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="https://cdn.assistant.moe/favicon/modelsaber/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="https://cdn.assistant.moe/favicon/modelsaber/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="https://cdn.assistant.moe/favicon/modelsaber/favicon-16x16.png">
  <link rel="manifest" href="https://cdn.assistant.moe/favicon/modelsaber/manifest.json">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="https://cdn.assistant.moe/favicon/modelsaber/favicon/ms-icon-144x144.png">
  <!-- End Favicon -->
  <meta http-equiv="refresh" content="5;url=${url}">
  <title>Upload Complete!</title>
  <link href="https://cdn.assistant.moe/css/bulma.css" media="screen" rel="stylesheet">
  <link href="https://cdn.assistant.moe/css/custom.css" rel="stylesheet">

  <link href="https://cdn.assistant.moe/css/light.css" id="theme" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.assistant.moe/js/theme.js"></script>
</head>
<body>
<section class="section">
  <div class="container">
    <h1 class="is-danger title is-1 has-text-centered">
      Upload Failed!
    </h1>
    <h1 class="is-danger title is-3 has-text-centered">
      ${message}
    </h1>
  </div>
</section>
</body>
</html>
EOF;
}

function success($url, $type, $id) {
  switch ($type) {
    case "'avatar'":
      $superType = 'Avatars';
      break;
    case "'saber'":
      $superType = 'Sabers';
      break;
    case "'platform'":
      $superType = 'Platforms';
      break;
  }
echo <<<EOF
<!DOCTYPE html>
<html lang="en">
<head>
  <meta content="text/html; charset=utf-8" http-equiv="content-type">
  <!-- Start Favicon-->
  <link rel="apple-touch-icon" sizes="57x57" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192"  href="https://cdn.assistant.moe/favicon/modelsaber/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="https://cdn.assistant.moe/favicon/modelsaber/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="https://cdn.assistant.moe/favicon/modelsaber/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="https://cdn.assistant.moe/favicon/modelsaber/favicon-16x16.png">
  <link rel="manifest" href="https://cdn.assistant.moe/favicon/modelsaber/manifest.json">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="https://cdn.assistant.moe/favicon/modelsaber/favicon/ms-icon-144x144.png">
  <!-- End Favicon -->
  <meta http-equiv="refresh" content="15;url=${url}">
  <title>Upload Complete!</title>
  <link href="https://cdn.assistant.moe/css/bulma.css" media="screen" rel="stylesheet">
  <link href="https://cdn.assistant.moe/css/custom.css" rel="stylesheet">

  <link href="https://cdn.assistant.moe/css/light.css" id="theme" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.assistant.moe/js/theme.js"></script>
</head>
<body>
<section class="section">
  <div class="container">
    <h1 class="title is-1 has-text-centered">
      Upload Complete!
    </h1>
    <h1 class="title is-3 has-text-centered">
      Please wait while we manually approve your submission.
      You will be able to see it <a href="${url}${superType}/?id=${id}">here</a> once it's approved. (save this)
    </h1>
  </div>
</section>
</body>
</html>
EOF;
}
?>