<?php
function apiv1Call($type, $ext) {
  require '../../../resources/filter.php';

  $host        = "host = 127.0.0.1";
  $port        = "port = 5432";
  $dbname      = "dbname = modelsaber";
  $credentials = "user = nginx password=RainbowdashBestPony";
  $db = pg_connect( "$host $port $dbname $credentials" );
  if(!$db) {
    exit;
  }

  $url = "https://modelsaber.com/files/".$type.'/';
  $filter = '*';
  $_start = 0;
  $_sort = '';


  if (isset($_GET['start'])) $_start = $_GET['start'];
  $_position = 'OFFSET ' . $_start;
  if (isset($_GET['end'])) $_position .= ' LIMIT ' . ($_GET['end'] - $_start);

  if (isset($_GET['filter'])) $filter = $_GET['filter'];


  if (isset($_GET['sort'])) {
    switch ($_GET['sort']) {
      case "date":
        $_sort = 'ORDER BY id';
        break;
      case "name":
        $_sort = 'ORDER BY name';
        break;
      case "author":
        $_sort = 'ORDER BY author';
        break;
      default:
        $_sort = 'ORDER BY id';
        break;
    }
  }
  if (isset($_sort)) {
    if (isset($_GET['sortDirection'])) {
      switch ($_GET['sortDirection']) {
        case "asc":
          $_sort .= ' ASC';
          break;
        case "desc":
          $_sort .= ' DESC';
          break;
      }
    }
  }

  $_filters = filter($db, $filter);

  $sqlQuery = <<<EOF
SELECT row_to_json(t)
FROM (
  SELECT  tags,
          type,
          name,
          author,
          image,
          filename,
          id,
          hash,
          bsaber
  FROM models
  WHERE approved='true' AND
  type = '$type' $_filters $_sort 
) t $_position
EOF;

  $response = pg_query($db, $sqlQuery);
  if(!$response) {
    echo pg_last_error($db);
    exit;
  }

  $object_string = '';

  while($row = pg_fetch_row($response)) {
    $object = json_decode($row[0]);
    $id = (string)$object->id;
    $object->image = $url.$id.'/'.$object->image;
    $object->download = $url.$id.'/'.$object->filename;
    $object->install_link = 'modsaber://'.$type.'/'.$object->download;
    unset($object->id);
    unset($object->filename);
    $object->date = $id;
    $object_string .= "\"$id\":" . json_encode($object, JSON_UNESCAPED_SLASHES) . ",";
  }

  $object_string = '{' . substr($object_string, 0, -1) . '}';

  header('Content-type: application/json');
  echo ($object_string);
  pg_close($db);
}
?>