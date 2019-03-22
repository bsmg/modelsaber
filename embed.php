<?php
function getEmbed ($type, $directID) {
  $url = "https://modelsaber.com/files/";
  $dir = "../files/";
  $host        = "host = 127.0.0.1";
  $port        = "port = 5432";
  $dbname      = "dbname = modelsaber";
  $credentials = "user = nginx password=password";
  $db = pg_connect( "$host $port $dbname $credentials" );
  if(!$db) {
    exit;
  }

  $sqlQuery = <<<EOF
  SELECT  name,
          author,
          image
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

  $thumgnail = str_replace("image", "thumbnail", $row[2]);
  $name = $row[0];
  $author = $row[1];

  if (!file_exists($dir.$type."/".$directID."/".$thumgnail)) {
    $oldFile = $dir.$type."/".$directID."/".$row[2]->image;
    $newFile = $dir.$type."/".$directID."/".$thumgnail;
    switch ($row[2]) {
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
  }

echo <<<EOF
  <!-- Start OEmbed --> 
  <meta content="ModelSaber" property="og:site_name">
  <meta content="$name" property="og:title">
  <meta content="$author" property="og:description">
  <meta content="#ebf4f9" name="theme-color">
  <meta content="https://modelsaber.com/files/$type/$directID/$thumgnail" property="og:image">
  <!-- End OEmbed -->
EOF;
}
?>