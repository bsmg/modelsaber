<?php
require_once 'resources/includes/constants.php';
require_once ROOT . '/user.php';

/**
 * Uploaded model class.
 * Used for a more object oriented structure for models.
 * 
 * @author Lauge Rud Knudsen <laugerudknudsen@gmail.com>
 * @since V2
 */
class Model {

  const PLATFORM_PC = 'pc';
  const PLATFORM_QUEST = 'quest';
  
  const FORMAT_UAB = 'uabe';
  const FORMAT_BMBF = 'bmbf';

  /** @var int The id of the model */
  protected $id;
  /** @var string The type of the model */
  protected $type;
  /** @var string The name of the model */
  protected $name;
  /** @var string The author of the model */
  protected $author;
  /** @var string The filename of the model */
  protected $filename;
  /** @var string The filename of the model's image */
  protected $image;
  /** @var string[] An array of strings */
  protected $tags;
  /** @var string Not sure what this one is tbh */
  protected $hash;
  /**
   * @var string Beast Saber username
   * @deprecated Moved to the {@see User} class
   * @see User
   */
  protected $bsaber;
  /** @var string The person that uploaded the model */
  protected $uploader;
  /** @var string Status of the model, example: 'unapproved', 'pending', or 'approved' */
  protected $status;
  /**
   * @var boolean The approval status of the model
   * @deprecated Replaced by {@see Model::$status}
   * @see Model::$status
   */
  protected $approved;
  /** @var string The description of the model */
  protected $description; //renamed from 'comments' for clarity
  /** @var string|null The discord userID of the uploader */
  protected $discordId;
  /** @var string The discord username and discriminator of the uploader */
  protected $discord;
  /** @var string|null The id of the variation this model is in */
  protected $variationId;
  /** @var string|null The title of the variation this model is in */
  protected $variationTitle;
  /** @var string[]|null A list with all of the models in this variation */
  protected $variationList;
  /** @var int Contains the total amount of pages on read */
  protected $totalRows;
  /** @var int Contains the curent page on a model read */
  protected $currentPage;
  /** @var string[]|null Contains the comments */
  protected $comments;
  /** @var string Contains the device platform name */
  protected $platform;
  /** @var string Contains the file format */
  protected $format;
  protected $upvotes = 0;
  protected $downvotes = 0;
  protected $downloads = 0;
  protected $updatedAt;
  
  
  //Getters
  public function getId() {
    return $this->id;
  }

  public function getType() {
    return $this->type;
  }

  public function getName() {
    return $this->name;
  }

  public function getAuthor() {
    return $this->author;
  }

  public function getFilename() {
    return $this->filename;
  }

  public function getImage() {
//    clearstatcache(true);
    $dir = WEBROOT . '/files/' . $this->type . '/' . $this->id . '/';
    $path = ROOT . '/files/' . $this->type . '/' . $this->id . '/';
    if (file_exists($path . 'image.svg')) {
      $output = $dir . 'image.svg';
    } else if (file_exists($path . 'image.gif')) {
      $output = $dir . 'image.gif';
    } else if (file_exists($path . 'image.jpg')) {
      $output = $dir . 'image.jpg';
    } else if (file_exists($path . 'image.png')) {
      $output = $dir . 'image.png';
    } else {
      if (file_exists($path . 'original.png')) {
        $output = $dir . 'original.png';
      } else if (file_exists($path . 'original.jpg')) {
        $output = $dir . 'original.jpg';
      } else if (file_exists($path . 'original.gif')) {
        $output = $dir . 'original.gif';
      } else {
        $output = false;
      }
      
    }
    return $output;
  }
  
  public function getVideo() {
//    clearstatcache(true);
    $dir = WEBROOT . '/files/' . $this->type . '/' . $this->id . '/';
    $path = ROOT . '/files/' . $this->type . '/' . $this->id . '/';
    
    if (file_exists($path . 'video.webm')) {
      $output[] = $dir . 'video.webm';
    }
    if (file_exists($path . 'video.mp4')) {
      $output[] = $dir . 'video.mp4';
    }
    
    if (!isset($output)) {
      $output = false;
    }
    return $output;
  }

  public function getTags() {
    return $this->tags;
  }

  public function getHash() {
    return $this->hash;
  }

  public function getBsaber() {
    return (isset($this->bsaber) && !empty($this->bsaber)) ? $this->bsaber : "";
  }

  public function getUploader() {
    return $this->uploader;
  }

  public function getApproved() {
    return $this->approved;
  }
  
  public function getStatus() {
    return $this->status;
  }

  public function getDescription() {
    return $this->description;
  }

  public function getDiscordId() {
    return $this->discordId;
  }

  public function getDiscord() {
    return $this->discord;
  }
  
  public function getVariationId() {
    return $this->variationId;
  }
  public function getVariationTitle() {
    return $this->variationTitle;
  }
  public function getLink() {
    $link = str_replace("?", "%3F", WEBROOT . '/files/' . $this->type . "/" . $this->id . "/" . rawurlencode($this->filename));
    return $link;
  }
  public function getTotalRows() {
    return $this->totalRows;
  }
  public function getCurrentPage() {
    return $this->currentPage;
  }
  public function getComments() {
    return $this->comments;
  }
  public function getUpvotes() {
    return $this->upvotes;
  }
  public function getDownvotes() {
    return $this->downvotes;
  }
  public function getDownloads() {
    return $this->downloads;
  }
  
  //Setters
  public function setTags($tags) {
    $this->tags = $tags;
  }

  public function setBsaber($bsaber) {
    $this->bsaber = $bsaber;
  }

  public function setDescription($description) {
    $this->description = $description;
  }
  
  //Methods
  function __construct($data = []) {
    if (count($data) > 0) {
      $this->id = $data['id'];
      $this->name = $data['name'];
      $this->author = $data['author'];
      $this->filename = $data['filename'];
//      $this->image = $data['image'];
      $this->tags = $data['tags'];
      $this->type = $data['type'];
      $this->status = $data['status'];
      $this->discord = $data['discord'];
      $this->discordId = $data['discordId'];
      if (isset($data['bsaber'])) {
        $this->bsaber = $data['bsaber'];
      }
      if (isset($data['description'])) {
        $this->description = $data['description'];
      }
      if (isset($data['hash'])) {
        $this->hash = $data['hash'];
      }
      if (isset($data['totalRows'])) {
        $this->totalRows = $data['totalRows'];
      }
      if (isset($data['variationId'])) {
        $this->variationId = $data['variationId'];
      }
      if (isset($data['variationTitle'])) {
        $this->variationTitle = $data['variationTitle'];
      }
      if (isset($data['upvotes'])) {
        $this->upvotes = $data['upvotes'];
      }
      if (isset($data['downvotes'])) {
        $this->downvotes = $data['downvotes'];
      }
      return $this;
    }
    return;
  }
    
  /** @return boolean Returns true if the $status of this model is 'approved' */
  public function isApproved() {
    return ($this->status == 'approved' || $this->approved == TRUE);
  }
  
  /** @return boolean Returns true if the specified user is the author of this model */
  public function isAuthor($userId) {
    return ($userId == $this->discordId);
  }
  
  public function hasVideoThumbnail() {
    $output = false;
    
    $path = ROOT . '/files/' . $this->type . '/' . $this->id . '/';
    if (file_exists($path . 'video.mp4') || file_exists($path . 'video.webm')) {
      $output = true;
    }
    
    return $output;
  }
  
  /**
   * Get the previous model in a variation list.
   * 
   * @param boolean $next If true the function will search the next index.
   * @return int|false The id of the requested model if found, false otherwise.
   */
  public function getPrevious($next = false) {
    $output = false;
    
    if (!empty($this->variationList)) {
      $index = array_search($this->id, $this->variationList);
      if ($next == true) {
        $index++;
      } else {
        $index--;
      }
      
      if (array_key_exists($index, $this->variationList)) {
        $output = $this->variationList[$index];
      }
    }
    
    return $output;
  }
  
  /**
   * Get the next model in a variation list.
   * 
   * @return int|false The id of the requested model if found, false otherwise.
   */
  public function getNext() {
    return $this->getPrevious(true);
  }
  
  /**
   * Create
   * 
   * Creates a new model
   * 
   * @param string $id The id of the model.
   * @param int $file The index of the file in the $_FILES superglobal
   * @param int $img The index of the image in the $_FILES superglobal
   * @param int|null $variationId The id of the variation group this model belongs to
   * @return type
   */
  public function create($model, $variationId = '') {
    global $currentUser;
    global $helper;
    
    FishyUtils::getInstance()->log("Creating model");
    $url = WEBROOT . '/';
    
    if (!$currentUser->isVerified()) {
      trigger_error('You must be logged in and have accepted the terms of service to upload');
      failed($url, 'You must be logged in and have accepted the terms of service to upload');
      die();
    }

//    $getInfo = $_POST;
    $this->id = $model['model']['id'];
    $variantTitle = $model['model']['variationTitle'];

    if (empty($this->id)) {
      trigger_error('ID isn\'t set', E_USER_ERROR);
      failed($url, '');
      die();
    }

    $this->platform = $model['upload']['platform'];
    $this->format = $model['upload']['format'];

    //File Setup
    /*$tmp_name = $_FILES['file']['tmp_name'][$file];
    $this->hash = md5_file($tmp_name);
    //put 2>&1 at the end of a exec call to get the error code
    exec("python getInfo.py " . $_FILES['file']['tmp_name'][$file] . "", $info);
    $this->type = $info[0];
    $this->name = $info[1];
    $this->author = $info[2];*/
    $this->hash = $model['model']['hash'];
    $this->type = $model['model']['type'];
    $this->name = $model['model']['name'];
    $this->author = $model['model']['author'];
//    $tmp_image_name = $_FILES['image']['tmp_name'][$img];
//    $image_Extension = pathinfo($_FILES['image']['name'][$img], PATHINFO_EXTENSION);

    $tags = "";
    if (isset($model['model']['tags']) && !empty($model['model']['tags'])) {
      foreach ($model['model']['tags'] as $tag) {
        if ($tag != "") {
          $tags .= $tag . ',';
        }
      }
    }
    

//Checks if any of the models are missing anything
    /*if (pathinfo($model['model']['author'], PATHINFO_FILENAME) != pathinfo($_FILES['file']['name'][$file], PATHINFO_FILENAME)) {
      trigger_error('Models and images don\'t match', E_USER_ERROR);
      failed($url, 'Models and images don\'t match');
      die();
    }*/
    switch ($this->type) {
      case "avatar":
        $ext = 'avatar';
        break;
      case "saber":
        $ext = 'saber';
        break;
        case "qsaber":
        $ext = 'qsaber';
        break;
      case "platform":
        $ext = 'plat';
        break;
      case "bloq":
      case "note":
        $ext = 'bloq';
        break;
      case "trail":
        $ext = 'trail';
        break;
      case "sign":
        $ext = 'sign';
        break;
      case "misc":
        $ext = 'misc';
        break;
      default:
        trigger_error('Upload format is invalid', E_USER_ERROR);
        failed($url, 'Upload format is invalid');
        die();
    }

    if ($this->platform == self::PLATFORM_QUEST && $this->format == self::FORMAT_BMBF) {
      $ext = 'zip';
    }

    if (!isset($this->hash) || empty($this->hash)) {
      trigger_error('Hash isn\'t set', E_USER_ERROR);
      failed($url, '');
      die();
    }
    if (!isset($this->name) || empty($this->name)) {
      trigger_error('Name isn\'t set', E_USER_ERROR);
      failed($url, '');
      die();
    }
    if (!isset($this->author) || empty($this->author)) {
      trigger_error('Author isn\'t set', E_USER_ERROR);
      failed($url, '');
      die();
    }
    
    if ($model['license'] == 'INVALID') {
      trigger_error('License is invalid', E_USER_ERROR);
      failed($url, 'License is invalid');
      die();
    }

    //Image Validation
    $imageClass = new Image($model['model']['image']['file'], $model['model']['image']['advanced'], $model['model']['image']['extension']);
    if (!$imageClass->isValid()) {
      trigger_error('Uploaded image is invalid');
      // failed($url, 'Image is invalid');
      die();
    }
    /*$imageSize = getimagesize($model['model']['image']['file']);
    $width = $imageSize[0];
    $height = $imageSize[1];
    if ($width != $height) {
      trigger_error('Image is not 1:1 aspect ratio', E_USER_ERROR);
      failed($url, 'Image is not 1:1 aspect ratio');
      die();
    }
    if ($width < MINIMAGESIZE) {
      trigger_error('Image is smaller than ' . MINIMAGESIZE, E_USER_ERROR);
      failed($url, 'Image is smaller than ' . MINIMAGESIZE);
      die();
    }
    switch ($model['model']['image']['extension']) {
      case "jpg":
        break;
      case "png":
        break;
      case "gif":
        break;
      default:
        trigger_error('Image format is invalid', E_USER_ERROR);
        failed($url, 'Image format is invalid');
        die();
    }*/
    
    $dir = '../files/' . $this->type . '/' . $this->id . '/';
    mkdir($dir);
    FishyUtils::getInstance()->log("Created directory named: $dir");
    $image_Extension = $imageClass->getFiletype();
    //File Upload
    $fileName = str_replace("/", "_", $this->name);
    if ($model['model']['image']['advanced'] == true) {
      if (file_put_contents($dir . $fileName . '.' . $ext, $model['model']['file']) == false) {
        $helper->deleteFiles($dir);
        trigger_error('Uploaded file failed to move');
        failed($url, 'Uploaded file failed to move');
        die();
      }
    } else {
      if (!move_uploaded_file($model['model']['file'], $dir . $fileName . '.' . $ext)) {
        $helper->deleteFiles($dir);
        trigger_error('Uploaded file failed to move');
        failed($url, 'Uploaded file failed to move');
        die();
      }
    }

    if ($model['model']['image']['advanced'] == true) {
      if (!imagepng($imageClass->getImage(), $dir . 'original.' . $image_Extension, 0)) {
        $helper->deleteFiles($dir);
        trigger_error('Uploaded image failed to move');
        failed($url, 'Uploaded image failed to moves');
        die();
      }
    } else {
      if (!move_uploaded_file($model['model']['image']['file'], $dir . 'original.' . $image_Extension)) {
        $helper->deleteFiles($dir);
        trigger_error('Uploaded image failed to move');
        failed($url, 'Uploaded image failed to move');
        die();
      }
    }
    if (!empty($model['embed'])) {
      $fp = fopen($dir . 'embed.json', 'w');
      fwrite($fp, json_encode($model['embed'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
      fclose($fp);
    }
    

    //Database Upload
    $id = $this->id;
    $type = $this->type;
    $this->name = $this->name;
    $this->author = $this->author;
    $fileName = $fileName . '.' . $ext;
    $this->hash = $this->hash;
    $this->platform = $this->platform;
    $this->format = $this->format;
    $imageName = $dir . 'original.' . $image_Extension;
//    $this->bsaber = pg_escape_literal($_POST['bsaber']);
    $this->description = strip_tags($model['model']['description']);
    $tags = '{' . substr($tags, 0, -1) . '}';
    if (!empty($variationId)) {
      $this->variationId = $variationId;
    } else {
      $this->variationId = '';
    }
    if ($currentUser->isTrusted()) {
      $status = APPROVED;
    } else {
      $status = UNAPPROVED;
    }

    //Discord
    $this->discordId = $currentUser->getDiscordId();
    $this->discord = $currentUser->getUsername() . '#' . $currentUser->getDiscriminator();
    
    $statement = dbConnection::getInstance()->prepare('INSERT INTO models ('
            . 'id, '
            . 'type, '
            . 'name, '
            . 'author, '
            . 'filename, '
            . 'tags, '
            . 'hash, '
            . 'comments, '
            . 'discordid, '
            . 'discord, '
            . 'status, '
            . 'platform, '
            . 'format, '
            . 'image, '
            . 'variationid) '
            . 'VALUES ('
            . ':id, '
            . ':type, '
            . ':name, '
            . ':author, '
            . ':filename, '
            . ':tags, '
            . ':hash, '
            . ':comments, '
            . ':discordid, '
            . ':discord, '
            . ':status, '
            . ':platform, '
            . ':format, '
            . ':image, '
            . "NULLIF(:variationid,'')::integer)");
    $statement->bindParam(':id', $id);
    $statement->bindParam(':type', $type);
    $statement->bindParam(':name', $this->name);
    $statement->bindParam(':author', $this->author);
    $statement->bindParam(':filename', $fileName);
    $statement->bindParam(':tags', $tags);
    $statement->bindParam(':hash', $this->hash);
    $statement->bindParam(':comments', $this->description);
    $statement->bindParam(':discordid', $this->discordId);
    $statement->bindParam(':discord', $this->discord);
    $statement->bindParam(':status', $status);
    $statement->bindParam(':platform', $this->platform);
    $statement->bindParam(':format', $this->format);
    $statement->bindValue(':image', $imageName);
    $statement->bindParam(':variationid', $this->variationId);
    
    $statement->execute();
    
    $isPrimary = true;
    if (!empty($variationId)) {
      if ($variationId == $this->id) {
        $variantTitle = (!empty($variantTitle)) ? $variantTitle : 'Unnamed Variation';
      $variant = '{' . $this->id . '}';
      $statement = dbConnection::getInstance()->prepare('INSERT INTO variations (variationid, modelids, title) '
              . 'VALUES (:variationid, :variant, :title)');
      $statement->bindParam(':variationid', $this->variationId);
      $statement->bindParam(':variant', $variant);
      $statement->bindParam(':title', $variantTitle);
    } else {
      $variant = $this->id;
      $statement = dbConnection::getInstance()->prepare('UPDATE variations '
              . 'SET modelids = array_append(modelids, :variant) '
              . 'WHERE variationid = :variationid');
      $statement->bindParam(':variationid', $this->variationId);
      $statement->bindParam(':variant', $variant);
      
      $isPrimary = false;
    }
    
    $statement->execute();
    }
    
    if ($isPrimary && !$currentUser->isTrusted()) {
      $admins = $currentUser->readAdmins();
      $notification = new notification();
      $notification->setLink(WEBROOT . '/' . getSuperType($this->type) . '?id=' . $this->id);
      $notification->create('A model is ready for approval', 'danger');
      $notification->addRelations($admins);
    }
    

    /*$thumbnail = str_replace("image", "thumbnail", $imageName);
    $oldFile = $imageName;
    $newFile = $thumbnail;
    switch ($imageName) {
      case "image.png":
      case "image.jpg":
        shell_exec("convert $oldFile -resize 64x64 $newFile");
        break;
      case "image.gif":
        shell_exec("convert $oldFile -layers Coalesce -resize 64x64 -layers Optimize $newFile");
        break;
    }
    unset($oldFile);
    unset($newFile);*/

    /*$user = new User();
    if (!$user->exists($this->discordId)) {
      $user->create($this->discordId, $discordUsername, $discordDiscriminator);
    }*/
    
    //Execute background script for image optimization
    $path = ROOT . '/files/' . $this->type . '/' . $this->id . '/';
    $imageName = 'original.' . $image_Extension;
    execInBackground(PHP_CLI . " " . ROOT . "/Upload/optimization.php " . $path . ' ' . $imageName);
    return $this->type;
  }
  
  public function readSingle($id, $useage = "model") {
    require_once ROOT . '/discordOAuth.php';
    require_once ROOT . '/resources/filter.php';
    global $sort;
    global $sort_dir;
    global $limit;
    global $total_rows;
    global $filter;
    global $page;
    global $type;
    global $status;
    global $offset;
    global $currentUser;

    FishyUtils::getInstance()->log("Reading single model with parameters: $id, $useage");
//    $directID = get('id');
    $directID = $id;

    if ($useage == 'Manage') {
      if (isset($_POST['modelType'])) {
        $type = filter_var($_POST['modelType'], FILTER_SANITIZE_EMAIL);
      } else {
        $type = 'all';
      }
    } else if ($useage == 'adminUpdate' || $useage == 'update') {
//      $directID = $_POST['action'];
    }

    if ($type == 'all') {
      $type = '(avatar|saber|platform|bloq|trail|sign|misc)';
    }

    if (isset($_POST['page']) && !empty($_POST['page'])) {
      $current_page = filter_var($_POST['page'], FILTER_SANITIZE_NUMBER_INT);
    } else {
      $current_page = 1;
    }
    if (isset($page) && !empty($page)) {
      $current_page = $page;
    } else {
      $page = 1;
    }

    $offset = (($current_page - 1) * $limit);

    if ($currentUser->isVerified()) {
      $discordID = $currentUser->getDiscordId();
    } else {
      $discordID = 0;
    }
    
    $devicePlatform = getDevicePlatform();
    $this->id = $directID;
    
    if ($currentUser->isVerified() && $currentUser->isAdmin()) {
      switch ($useage) {
        case 'Manage':
          $where = "";
          break;
        default:
          $where = "";
          break;
      }
    } else {
      $where = "AND
    (((m.approved='true' OR m.status='approved') OR m.discordid='-1') OR
    ((m.approved='false' OR m.status!='approved') AND
    m.discordid='$discordID'))";
    }
    
    $statement = dbConnection::getInstance()->prepare('SELECT '
            . 'm.name, '
            . 'm.author, '
            . 'm.filename, '
            . 'm.image, '
            . 'm.tags, '
            . 'm.hash, '
            . 'm.bsaber, '
            . 'm.uploader, '
            . 'm.approved, '
            . 'm.comments, '
            . 'm.discordid, '
            . 'm.discord, '
            . 'm.status, '
            . 'm.type, '
            . 'm.variationid, '
            . 'm.updatedat, '
            . 'd.amount AS downloads, '
            . '(SELECT COUNT(v.modelid) FROM votes as v WHERE v.modelid = m.id AND v.isupvote = true) AS upvotes, '
            . '(SELECT COUNT(v.modelid) FROM votes as v WHERE v.modelid = m.id AND v.isupvote = false) AS downvotes '
            . 'FROM models AS m '
            . 'LEFT JOIN downloads AS d ON m.id = d.modelid '
            . "WHERE m.id = :directid $where");
    $statement->bindParam(':directid', $directID);
    
    $statement->execute();

    while ($row = $statement->fetch()) {
      $this->name = $row['name'];
      $this->author = $row['author'];
      $this->filename = $row['filename'];
  //    $this->image = $row[3];
      $this->tags = explode(',', str_replace("\"", "", substr($row['tags'], 1, -1)));
      $this->hash = $row['hash'];
      $this->bsaber = $row['bsaber'];
      $this->uploader = $row['uploader']; //unused
      $this->approved = ($row['approved'] == 't') ? true : false;
      $this->description = $row['comments'];
      $this->discordId = $row['discordid'];
      $this->discord = $row['discord'];
      $this->type = $row['type'];
      $this->variationId = $row['variationid'];
      $this->downloads = (!empty($row['downloads'])) ? $row['downloads'] : 0;
      $this->upvotes = (!empty($row['upvotes'])) ? $row['upvotes'] : 0;
      $this->downvotes = (!empty($row['downvotes'])) ? $row['downvotes'] : 0;
      $this->updatedAt = $row['updatedat'];
      if (!empty($row['status'])) {
        $this->status = $row['status'];
      } else {
        if ($this->approved) {
          $this->status = APPROVED;
        } else {
          $this->status = UNAPPROVED;
        }
      }
      if (!empty($this->variationId)) {
        $variation = $this->variationId;
        $statement = dbConnection::getInstance()->prepare('SELECT modelids, title '
                . 'FROM variations '
                . 'WHERE modelids @> ARRAY[CAST(:variation AS bigint)]');
        $statement->bindParam(':variation', $variation);
        
        $statement->execute();
        
        while ($row = $statement->fetch()) {
          $this->variationList = explode(',', str_replace("\"", "", substr($row['modelids'], 1, -1)));
          $this->variationTitle = $row['title'];
        }
        
      }
    }

    
    $this->image = WEBROOT . '/files/' . $this->type . '/' . $this->id . '/';
    FishyUtils::getInstance()->log("Finished reading single model with parameters: $id, $useage");
    return true;
  }

  public function readMultiple($mode = 'print', $useage = "model") {
    require_once ROOT . '/discordOAuth.php';
    require_once ROOT . '/resources/filter.php';
    global $sort;
    global $sort_dir;
    global $limit;
    global $total_rows;
    global $filter;
//    global $page;
    global $type;
    global $status;
    global $offset;
    global $currentUser;

    FishyUtils::getInstance()->log("Reading multiple models with parameters: $mode, $useage");

    if ($useage == 'Manage') {
      if (isset($_POST['modelType'])) {
        $type = filter_var($_POST['modelType'], FILTER_SANITIZE_STRING);
      } else {
        $type = 'all';
      }
    }

    if (isset($_POST['page']) && !empty($_POST['page'])) {
      $current_page = filter_var($_POST['page'], FILTER_SANITIZE_NUMBER_INT);
    } else {
      $current_page = 1;
    }
    /* if (isset($page) && !empty($page)) {
      $current_page = $page;
      } else {
      $page = 1;
      } */

    $offset = (($current_page - 1) * $limit);
    if ($currentUser->isVerified()) {
      $discordID = $currentUser->getDiscordId();
    } else {
      $discordID = 0;
    }

    if ($useage == 'Profile') {
      if (isset($_GET['user']) && !empty($_GET['user'])) {
        $userId = $_GET['user'];
      } else if (isset($_POST['user']) && !empty($_POST['user'])) {
        $userId = $_POST['user'];
      }
      $type = 'all';
    }
    
    if ($type == 'all') {
      $type = '(avatar|saber|platform|bloq|trail|sign|misc)';
    }

    
    if (getDevicePlatform() == 'quest') {
      $platform = "m.platform LIKE 'quest' AND ";
    } else {
      $platform = "(m.platform NOT LIKE 'quest' OR m.platform IS NULL) AND ";
    }
    
    $where = "((m.approved='true' OR m.status='approved') OR
    ((m.approved='false' OR m.status!='approved') AND
    m.discordid='$discordID')) AND";
    $manager = false;
    
    if ($useage == 'Variation') {
      $variation = $_GET['variation'];
      $variation = "m.variationid = CAST($variation AS bigint) AND ";
    } else {
      $variation = ' (m.variationid = m.id OR m.variationid IS NULL) AND ';
    }
    
    switch ($useage) {
      case 'Manage':
        if (!$currentUser->isVerified() || !$currentUser->isAdmin()) {
          break;
        }
        $where = "m.status='$status' AND";
        $manager = true;
        break;
      case 'Profile':
        $where = "m.discordid = CAST($userId AS bigint) AND" . $where;
        break;
      default:
        if ($currentUser->isVerified() && $currentUser->isAdmin()) {
          $where = "";
        }
        break;
    }
//    $_filters = filter($filter);
    $_filtersAr = filter($filter);
    $_filters = $_filtersAr['query'];
    if (!isset($type)) {
      $type = strtolower(getUrlType());
      $type = (substr($type, 0, strlen($type) - 1) == 's') ? $type : substr($type, 0, -1);
    }

    if ($manager) {
      $statement = dbConnection::getInstance()->prepare('SELECT '
              . 'm.id, '
              . 'm.name, '
              . 'm.author, '
              . 'm.filename, '
              . 'm.image, '
              . 'm.tags, '
              . 'count(m.id) OVER() AS full_count, '
              . 'm.status, '
              . 'm.approved, '
              . 'm.discord, '
              . 'm.discordid, '
              . 'm.type, '
              . 'm.variationid, '
              . 'm.bsaber, '
              . 'm.comments AS description, '
              . 'm.hash, '
              . '(SELECT COUNT(v.modelid) FROM votes as v WHERE v.modelid = m.id AND v.isupvote = true) AS votes, '
              . '(SELECT COUNT(v.modelid) FROM votes as v WHERE v.modelid = m.id AND v.isupvote = false) AS downvotes '
              . 'FROM models AS m '
              . 'LEFT JOIN variations AS v ON m.variationid = v.variationid '
              . 'LEFT JOIN users AS u ON m.discordid = u.discordid '
              . "WHERE $where "
              . "m.type ~ :type $_filters "
              . "ORDER BY $sort $sort_dir "
              . "LIMIT $limit "
              . "OFFSET $offset");
      // $statement->bindParam(':type', $type);
      // $statement->bindParam(':sort', $sort);
      // $statement->bindParam(':sortdir', $sort_dir);
      // $statement->bindParam(':limit', $limit);
      // $statement->bindParam(':offset', $offset);
      $_filtersAr['params'][':type'] = $type;
    } else {
      $statement = dbConnection::getInstance()->prepare('SELECT '
              . 'm.id, '
              . 'm.name, '
              . 'm.author, '
              . 'm.filename, '
              . 'm.image, '
              . 'm.tags, '
              . 'count(m.id) OVER() AS full_count, '
              . 'm.status, '
              . 'm.approved, '
              . 'm.discord, '
              . 'm.discordid, '
              . 'm.type, '
              . 'm.variationid, '
              . 'v.title AS variationtitle, '
              . '(SELECT COUNT(v.modelid) FROM votes as v WHERE v.modelid = m.id AND v.isupvote = true) AS votes, '
              . '(SELECT COUNT(v.modelid) FROM votes as v WHERE v.modelid = m.id AND v.isupvote = false) AS downvotes '
              . 'FROM models AS m '
              . 'LEFT JOIN variations AS v ON m.variationid = v.variationid '
              . 'LEFT JOIN users AS u ON m.discordid = u.discordid '
              . "WHERE $variation $where "
              . "$platform "
              . "m.type ~ :type $_filters "
              . "ORDER BY $sort $sort_dir "
              . "LIMIT $limit "
              . "OFFSET $offset");
//      $statement->bindParam(':type', $type);
      $_filtersAr['params'][':type'] = $type;
    }

    $statement->execute($_filtersAr['params']);

    while ($row = $statement->fetch()) {
      $modelTemp['id'] = $row['id'];
      $modelTemp['name'] = $row['name'];
      $modelTemp['author'] = $row['author'];
      $modelTemp['filename'] = $row['filename'];
//      $modelTemp['image'] = $row['image'];
      $modelTemp['tags'] = explode(',', str_replace("\"", "", substr($row['tags'], 1, -1)));
      $modelTemp['type'] = $row['type'];
      $isStatusApproved = false;
      if (!empty($row['status'])) {
        $modelTemp['status'] = $row['status'];
        if ($row['status'] == APPROVED || $row['status'] == VERIFIED) {
          $isStatusApproved = true;
        }
        
      }
      if (!$isStatusApproved && !empty($row['approved'])) {
        $modelTemp['status'] = ($row['approved'] == 't') ? APPROVED : UNAPPROVED;
      }
      $modelTemp['discord'] = $row['discord'];
      $modelTemp['discordId'] = $row['discordid'];
      $modelTemp['totalRows'] = $row['full_count'];
      $modelTemp['currentPage'] = $current_page;

      if (isset($row['variationid'])) {
        $modelTemp['variationId'] = $row['variationid'];
      }
      if (isset($row['variationtitle'])) {
        $modelTemp['variationTitle'] = $row['variationtitle'];
      }
      if (isset($row['bsaber'])) {
        $modelTemp['bsaber'] = $row['bsaber'];
      }
      if (isset($row['description'])) {
        $modelTemp['description'] = $row['description'];
      }
      if (isset($row['hash'])) {
        $modelTemp['hash'] = $row['hash'];
      }
      $modelTemp['upvotes'] = (!empty($row['votes'])) ? $row['votes'] : 0;
      $modelTemp['downvotes'] = (!empty($row['downvotes'])) ? $row['downvotes'] : 0;
//      $modelTemp['image'] = WEBROOT . '/files/' . $row['type'] . '/' . $row['id'] . '/';

      $temp = new Model($modelTemp);
      unset($modelTemp);
      $modelList[] = $temp;
      unset($row);
    }
    if (!isset($modelList)) {
      FishyUtils::getInstance()->log("Finished reading multiple models with parameters: $mode, $useage. Models read: 0");
      return "No models were found";
    }
    FishyUtils::getInstance()->log("Finished reading multiple models with parameters: $mode, $useage. Models read: " . count($modelList));
    return $modelList;
  }
  
  public function readStats() {
    $statement = dbConnection::getInstance()->prepare('SELECT '
            . 'd.amount AS downloads, '
            . '(SELECT COUNT(v.modelid) FROM votes AS v WHERE v.modelid = d.modelid AND v.isupvote = true) AS upvotes, '
            . '(SELECT COUNT(v.modelid) FROM votes AS v WHERE v.modelid = d.modelid AND v.isupvote = false) AS downvotes '
            . 'FROM downloads AS d '
            . 'WHERE d.modelid = :id');
    $statement->bindParam(':id', $this->id);
    
    $statement->execute();

    while ($row = pg_fetch_row($response)) {
      $this->downloads = (!empty($row[0])) ? $row[0] : 0;
      $this->upvotes = (!empty($row[1])) ? $row[1] : 0;
      $this->downvotes = (!empty($row[2])) ? $row[2] : 0;
    }
    
  }

  /**
   * Update this model.
   * 
   * @return bool
   */
  public function update($values) {
    global $currentUser;

    $output = false;
    $errored = false;
    $shouldPostWebhook = false;

    FishyUtils::getInstance()->log("Updating Model with id of $this->id");
    if (!$currentUser->isVerified()) {
      trigger_error('The $currentUser is not logged in', E_USER_ERROR);
      $errored = true;
    }

    $selfApprove = ($this->isAuthor($currentUser->getDiscordId()));
    $adminAction = ($currentUser->isAdmin() && ($_POST['action'] == 'adminUpdate' || $_POST['action'] == 'adminUpdateSingle'));
    $adminUpdate = ($adminAction && !$selfApprove);
    
    //the '||' below might need to be changed to a '&&'
    // if (!$this->isAuthor($currentUser->getDiscordId()) && !$currentUser->isAdmin()) {
    //   trigger_error('The $currentUser is not the author or an admin', E_USER_ERROR);
    //   $errored = true;
    // }
    // self approving checks
    if ($adminAction) {
      if ($selfApprove) {
        trigger_error('The $currentUser can not approve their own models!', E_USER_ERROR);
        $errored = true;
      }
    } else {
      if (!$selfApprove) {
        trigger_error('The $currentUser may only update their own models!', E_USER_ERROR);
        $errored = true;
      }
    }

    if (!$errored) {
      //User:  tags description image file
      //Admin: tags description status discordid variationid
      $id = $this->id;

      $tagArray = explode(',', $values['tags']);
      $tagArray = array_filter($tagArray);
      $tagArray = array_map(function($item) {
        return (strpos($item, ' ') !== false) ? '"' . $item . '"' : $item;
      }, $tagArray);
      // $tags = '{' . substr($values['tags'], 0) . '}';
      $tags =  '{' . join(',', $tagArray) . '}';
      $this->description = strip_tags($values['description']);
      
      // $selfApprove = ($this->isAuthor($currentUser->getDiscordId()));
      // $adminUpdate = ($currentUser->isAdmin() && ($_POST['action'] == 'adminUpdate' || $_POST['action'] == 'adminUpdateSingle') && !$selfApprove);
      if ($adminUpdate) {
        //set admin only values
        if (isset($values['status']) && $currentUser->canApprove()) {
          $this->status = $values['status'];
          $shouldPostWebhook = empty($this->updatedAt);
        }
        
        if (isset($values['discordId'])) {
          $this->discordId = $values['discordId'];
        }
        if (empty($this->discordId)) {
          $this->discordId = -1;
        }
        
        if (isset($values['variationId'])) {
          $variationId = $values['variationId'];
        }
        
        $updatedAt = time();
      } else {
        //check if the user is trusted
        if ($currentUser->isTrusted()) {
          $this->status = APPROVED;
        } else {
          $this->status = UNAPPROVED;
        }
      }

      // set the appropriate approved status
      switch ($this->status) {
        case VERIFIED:
          $this->approved = 'true';
          break;
        case APPROVED:
          $this->approved = 'true';
          break;
        case UNAPPROVED:
          $this->approved = 'false';
          break;
        case DECLINED:
          $this->approved = 'false';
          break;
        default:
          $this->approved = 'false';
          break;
      }
      
      $statement = dbConnection::getInstance()->prepare('UPDATE models '
              . 'SET tags = :tags, status = :status, approved = :approved, discordid = :discordid, '
              . "comments = :description "
              . 'WHERE id = :id');
      $statement->bindParam(':tags', $tags);
      $statement->bindValue(':status', $this->status);
      $statement->bindParam(':approved', $this->approved);
      $statement->bindParam(':discordid', $this->discordId);
      $statement->bindParam(':description', $this->description);
      $statement->bindParam(':id', $id);

      $statement->execute();
      
      //variationId's were being a bitch and couldn't accept null values so i
      //had to make another database call.
      if (empty($variationId) || $this->variationId == $variationId) {
        $statement = dbConnection::getInstance()->prepare('UPDATE models '
              . 'SET variationid = :variationid '
              . 'WHERE id = :id');
        $statement->bindParam(':variationid', $variationId);
        $statement->bindParam(':id', $id);

        $statement->execute();
        $statement = dbConnection::getInstance()->prepare('UPDATE variations '
              . 'SET modelids = array_append({:id}, modelids) '
              . 'WHERE variationid = :variationid');
        $statement->bindParam(':variationid', $variationId);
        $statement->bindParam(':id', $id);

        $statement->execute();
      }
      
      if ($adminUpdate) {
        // update updateAt
        $statement = dbConnection::getInstance()->prepare('UPDATE models '
                . 'SET updatedat = :updatedat '
                . 'WHERE id = :id');
        $statement->bindParam(':updatedat', $updatedAt);
        $statement->bindParam(':id', $id);

        $statement->execute();

        //send a notification
        if (!empty($this->discordId) && ($values['status'] == DECLINED || $values['status'] == APPROVED)) {
          $notification = new notification($this->discordId);
          $notification->setLink(WEBROOT . '/' . getSuperType($this->type) . '?id=' . $this->id);
        }
        
        if ($values['status'] == DECLINED) {
          if (!empty($this->discordId)) {
            $notification->create('Model rejected', 'warning', $values['notifMessage']);
          }
        } else if ($values['status'] == APPROVED) {
          if (!empty($this->discordId)) {
            $notification->create('Model approved', 'warning', $values['notifMessage']);
          }
          
          if ($shouldPostWebhook) {
            $webhook = new Webhook($this->id);
            $webhook->post();
          }
        }
      } else {
        if (!empty($values['image'])) {
          $this->updateImage($values['image']);
        }
        if (!empty($values['file'])) {
          $this->updateFile($values['file']);
        }
        if (!empty($values['unityproject'])) {
          $this->updateUnityPackage($values['unityproject']);
        }
      }
    }
    
    FishyUtils::getInstance()->log("Finished updating model with id of $this->id with status of $output");
    return $output;
  }

  /**
   * Deletes this model from the database.
   * 
   * @return bool
   */
  public function delete() {
    global $currentUser;
    
    $output = false;
    $errored = false;
    
    FishyUtils::getInstance()->log("Deleting Model with id of $this->id");
    if (!$currentUser->isVerified()) {
      trigger_error('The $currentUser is not logged in', E_USER_ERROR);
      $errored = true;
    }
    if (!$this->isAuthor($currentUser->getDiscordId()) && !$currentUser->isAdmin()) {
      trigger_error('The $currentUser is not the author or an admin', E_USER_ERROR);
      $errored = true;
    }
    
    if ($errored === false) {      
      $statement = dbConnection::getInstance()->prepare('DELETE FROM models '
              . 'WHERE id = CAST(:id AS bigint)');
      $statement->bindParam(':id', $this->id);
      
      $output = $statement->execute();
    }
    
    FishyUtils::getInstance()->log("Finished deleting model with id of $this->id with status of $output");
    return $output;
  }
  
  private function updateFile($file) {
    global $currentUser;
    
    $url = WEBROOT . '/';

    //File Setup
    $tmp_name = $file['tmp_name'];
    $this->hash = md5_file($tmp_name);
    //put 2>&1 at the end of a exec call to get the error code
    exec("python getInfo.py " . $file['tmp_name'] . "", $info);
    $type = $info[0];
    $name = $info[1];
    $author = $info[2];
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);

//Checks if any of the models are missing anything
    if ($type !== $this->type) {
      trigger_error('Uploaded format does not match', E_USER_ERROR);
      failed($url, 'Uploaded format does not match');
      die();
    }
    if ($name !== $this->name) {
      trigger_error('Uploaded model name does not match', E_USER_ERROR);
      failed($url, 'Uploaded model name does not match');
      die();
    }
    if ($author !== $this->author) {
      trigger_error('Uploaded author does not match', E_USER_ERROR);
      failed($url, 'Uploaded author does not match');
      die();
    }
    if (!isset($this->hash)) {
      trigger_error('Hash isn\'t set', E_USER_ERROR);
      failed($url, '');
      die();
    }

    
    if (!$currentUser->isVerified()) {
      trigger_error('You must be logged in and have a accepted the terms of service to upload');
      failed($url, 'You must be logged in and have a accepted the terms of service to upload');
      die();
    }
    if (!$this->isAuthor($currentUser->getDiscordId())) {
      trigger_error('Only the author can update their models');
      failed($url, 'Only the author can update their models');
      die();
    }
    $dir = '../files/' . $this->type . '/' . $this->id . '/';

    //File Upload
    if(file_exists($dir . $this->filename)) {
      chmod($dir . $this->filename,0755); //Change the file permissions if allowed
      unlink($dir . $this->filename); //remove the old file
    }
    if (!move_uploaded_file($tmp_name, $dir . $this->fileName)) {
      trigger_error('Uploaded file failed to move');
      die();
    }
    
    //Database Upload
    if (!$currentUser->isTrusted()) {
      $this->status = UNAPPROVED;
    }
    $statement = dbConnection::getInstance()->prepare('UPDATE models '
            . 'SET hash = :hash, status = :status '
            . 'WHERE id = :id');
    $statement->bindParam(':hash', $this->hash);
    $statement->bindParam(':status', $this->status);
    $statement->bindParam(':id', $this->id);
    
    $statement->execute();
  }

  private function updateImage($image) {
    global $currentUser;
    
    $tmp_image_name = $image['tmp_name'];

    $dir = ROOT . '/files/' . $this->type . '/' . $this->id . '/';
    $dirDB = WEBROOT . '/files/' . $this->type . '/' . $this->id . '/';

    //Image Validation
    $imageClass = new Image($tmp_image_name);
    if (!$imageClass->isValid()) {
      trigger_error('Uploaded image is invalid');
      die();
    }

    //Delete old images
    $imageNames = ['original.', 'image.', 'thumbnail.', 'video.'];
    foreach ($imageNames as $imgName) {
      foreach (SUPPORTEDIMAGES as $filetype) {
        $path = $dir . $imgName . $filetype;
        if (file_exists($path)) {
          chmod($path, 0755); //Change the file permissions if allowed
          unlink($path); //remove the old image
        }
      }
    }
    $image_Extension = $imageClass->getFiletype();

    //Image Upload
    if (!move_uploaded_file($tmp_image_name, $dir . 'original.' . $image_Extension)) {
      trigger_error('Uploaded image failed to move');
      die();
    }

    //Set the model status to be unapproved
    if (!$currentUser->isTrusted()) {
      $this->status = UNAPPROVED;
    }
    $statement = dbConnection::getInstance()->prepare('UPDATE models '
              . 'SET status = :status, image = :image '
              . 'WHERE id = :id');
      $statement->bindParam(':status', $this->status);
      $statement->bindValue(':image', $dirDB . 'original.' . $image_Extension);
      $statement->bindParam(':id', $this->id);
    
      $statement->execute();


    //Execute background script for image optimization
    $path = ROOT . '/files/' . $this->type . '/' . $this->id . '/';
    $imageName = 'original.' . $image_Extension;
    execInBackground(PHP_CLI . " " . ROOT . "/Upload/optimization.php " . $path . ' ' . $imageName);
    return true;
  }
  
  private function updateUnityPackage($package) {
    global $currentUser;
    
    $dir = '../files/' . $this->type . '/' . $this->id . '/';
    $filename = pathinfo($this->filename, PATHINFO_FILENAME) . '.unitypackage';

    //File Upload
    if(file_exists($dir . $filename)) {
      chmod($dir . $filename,0755); //Change the file permissions if allowed
      unlink($dir . $filename); //remove the old file
    }
    if (!move_uploaded_file($package['tmp_name'], $dir . $filename)) {
      trigger_error('Uploaded unityproject failed to move');
      die();
    }
    
    //Database Upload
    if (!$currentUser->isTrusted()) {
      $this->status = UNAPPROVED;
    }
    $statement = dbConnection::getInstance()->prepare('UPDATE models '
            . 'SET status = :status '
            . 'WHERE id = :id');
    $statement->bindParam(':status', $this->status);
    $statement->bindParam(':id', $this->id);
    
    $statement->execute();
  }

  public function readComments() {
    FishyUtils::getInstance()->log('Reading Comments');
    
    $id = $this->id;
    
    $statement = dbConnection::getInstance()->prepare('SELECT commentids '
            . 'FROM models '
            . 'WHERE id = CAST(:id AS bigint)');
    $statement->bindParam(':id', $id);
    
    $statement->execute();
    
    while ($row = $statement->fetch()) {
      if (!empty($row['commentids']) && count($row['commentids']) > 0) {
        $comments = explode(',', str_replace("\"","", substr($row['commentids'], 1, -1)));
        
        $comments = array_filter($comments);

        $this->comments = $comments;
      }
    }
    
    
  }
  
  public function getNotification($title) {
    $output['image'] = $this->getImage();
    $output['icon'] = $this->getImage();
    $output['badge'] = WEBROOT . '/resources/manifest/icon-192.png';
    $output['title'] = $title;
    $output['body'] = substr($this->description, 0, 20) . '...';
    
    return json_encode($output, JSON_UNESCAPED_SLASHES);
  }

}
