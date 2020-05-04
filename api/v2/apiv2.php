<?php
require_once '../../helper.php';
require_once '../../dbConnection.php';
function apiCall() {
  dbConnection::getInstance()->start();
  require 'filter.php';

  $url = Helper::getInstance()->setting('WEBROOT') . "/files/";
  $filter = '*';
  $_start = 0;
  $_sort = '';
  $type = 'all';
  $platform = 'all';

  if (isset($_GET['type'])) $type = $_GET['type'];
  if (isset($_GET['platform'])) $platform = $_GET['platform'];
  if (isset($_GET['start'])) $_start = $_GET['start'];
  $_position = 'OFFSET ' . $_start;
  if (isset($_GET['end'])) $_position .= ' LIMIT ' . ($_GET['end'] - $_start);

  if (isset($_GET['filter'])) $filter = $_GET['filter'];

  // $filter = str_replace(['%20', '+'], ' ', $filter);

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
  
  if ($type == 'all') {
    $type = '(avatar|saber|platform|bloq|misc)';
  }

  switch ($platform) {
    case 'all':
      $platform = "";
      break;
    case 'pc':
      $platform = "(m.platform NOT LIKE 'quest' OR m.platform IS NULL) AND";
      break;
    case 'quest':
      $platform = "m.platform LIKE 'quest' AND";
      break;
  }

  $_filters = filter($filter);
  // echo $_filters;
  // var_dump($_position);
  // exit;

  $statement = dbConnection::getInstance()->prepare('SELECT row_to_json(t) '
          . 'FROM ('
          . 'SELECT '
          . 'm.tags, '
          . 'm.type, '
          . 'm.name, '
          . 'm.author, '
          . 'm.image AS thumbnail, '
          . 'm.filename, '
          . 'm.id, '
          . 'm.hash, '
          . 'm.bsaber, '
          . 'm.status, '
          . 'CAST(m.discordid AS text), '
          . "u.username || '#' || u.discriminator AS discord, "
          . 'u.roles, '
          . 'm.variationid, '
          . 'u.bsaber AS ubsaber, '
          . 'm.platform '
          . 'FROM models AS m '
          . 'LEFT JOIN users AS u ON m.discordid = u.discordid '
          . "WHERE (m.approved = 'true' OR m.status = 'approved') AND "
          // . "WHERE m.approved = 'true' AND "
          . "$platform m.type ~ :type $_filters $_sort"
          . ") t $_position");
  $statement->bindParam(':type', $type);
  // $statement->bindParam(':platform', $platform);
  
  $statement->execute();

  $object_string = '';

  while($row = $statement->fetch()) {
    $object = json_decode($row[0]);
    $id = (string)$object->id;
    $object->thumbnail = (strpos($object->thumbnail, '..') === FALSE) ? $object->thumbnail : str_replace('../files/', $url, $object->thumbnail);
    $object->download = $url.$object->type.'/'.$id.'/'.$object->filename;
    $object->install_link = 'modelsaber://'.$object->type.'/'.$id.'/'.$object->filename;
    $object->bsaber = (empty($object->ubsaber)) ? $object->bsaber : $object->ubsaber;
    $object->platform = (!empty($object->platform)) ? $object->platform : 'pc';
    unset($object->filename);
    unset($object->roles);
    unset($object->ubsaber);
    $object->date = date('Y-m-d h:i:s', $id) . ' UTC'; //'Y-m-d H:i '
    $object_string .= "\"$id\":" . json_encode($object, JSON_UNESCAPED_SLASHES) . ",";
  }

  $object_string = '{' . substr($object_string, 0, -1) . '}';

  header('Content-type: application/json');
  echo ($object_string);
  dbConnection::getInstance()->close();
}

function types() {
  //PC
  //UAB
  $supported['PC']['UAB'][0]['type'] = 'avatar';
  $supported['PC']['UAB'][0]['directory'] = 'CustomAvatars';
  $supported['PC']['UAB'][1]['type'] = 'saber';
  $supported['PC']['UAB'][1]['directory'] = 'CustomSabers';
  $supported['PC']['UAB'][2]['type'] = 'platform';
  $supported['PC']['UAB'][2]['directory'] = 'CustomPlatforms';
  $supported['PC']['UAB'][3]['type'] = 'bloq';
  $supported['PC']['UAB'][3]['directory'] = 'CustomNotes';
  
  //Quest
  //BMBF
  $supported['Quest']['BMBF'] = ['saber', 'platform', 'bloq', 'misc'];
  
  //Print
  header('Content-type: application/json');
  echo json_encode($supported, JSON_UNESCAPED_SLASHES);
}
?>