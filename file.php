<?php

/**
 * A class specifically designed to take care of file uploads.
 *
 * @author Lauge Rud Knudsen <laugerudknudsen@gmail.com>
 * @version V2
 */
class file {
  private $id;
  private $file;
  private $complexity;
  private $count = 0;
  private $platform;
  private $format;
  private $license;
  private $zip;
  private $beatOn;
  private $modelType;
  private $settings;
  private $image;
  private $tags;
  private $description;
  private $model = [];
  
  //public getters
  public function getModelType() {
    return $this->modelType;
  }
  
  public function getImage() {
    return $this->image;
  }
  
  public function getTags() {
    return $this->tags;
  }
  
  public function getDescription() {
    return $this->description;
  }
  
  public function getLicense() {
    return $this->license;
  }
  
  public function getModel() {
    return $this->model;
  }
  
  public function __get($prop) {
    return $this->$prop;
  }

  public function __isset($prop) : bool {
    return isset($this->$prop);
  }
  
  //setters
  protected function setTags() {
    $this->model['model']['tags'] = (isset($_POST['tags'])) ? $_POST['tags'] : "";
  }
  
  protected function setDescription() {
    $this->model['model']['description'] = $_POST['comments'];
  }
  
  public function setImage($image = "") {
    if ($this->complexity == 'advanced') {
      foreach (SUPPORTEDIMAGES as $type) {
        $imageName = pathinfo($this->file['name'], PATHINFO_FILENAME) . '.' . $type;
        if ($this->zip->locateName($imageName) !== false) {
          $this->model['model']['image']['file'] = $this->zip->getFromName($imageName);
          $this->model['model']['image']['extension'] = $type;
          $this->model['model']['image']['advanced'] = true;
          return;
        }
      }
    } else if ($this->complexity == 'simple') {
//      $this->model['model']['image'] = file_get_contents($image);
      $this->model['model']['image']['file'] = $image['tmp_name'];
      $this->model['model']['image']['extension'] = pathinfo($image['name'], PATHINFO_EXTENSION);
      $this->model['model']['image']['advanced'] = false;
      return;
    }
  }

  public function setVariationTitle($variationTitle = 'Unnamed Variation') {
    $this->model['model']['variationTitle'] = $variationTitle;
  }
  
  
  //construct
  
  function __construct($file, $complexity, $id) {
    $this->file = $file;
    $this->complexity = $complexity;
    $this->id = $id;
    $this->model['model']['id'] = $this->id;
  }
  
  //functions
  
  public function create() {
    if (!$this->setModelType()) {
      return false;
    }

    if ($this->isZip()) {
      $this->readFromZip();
    } else {
      $this->readFromFile();
    }

    return true;
  }
  
  private function readFromZip() {
    // $this->zip = new ZipArchive();
    // if ($this->zip->open($this->file['tmp_name']) === true) {

      if ($this->platform == Model::PLATFORM_PC) {
        if ($this->format == Model::FORMAT_UAB) {
          $this->readSettings();
          $this->model['embed'] = $this->settings['embed'];
          $tempfile = 'tempfile.' . typeToExtension($this->modelType);
          $handle = fopen($tempfile, 'w');
//        $fb = fopen('zip://' . $file['tmp_name'] . '#' . pathinfo($file['name'], PATHINFO_FILENAME) . '.' . TypeToPath($filetype), 'rb');
//          $data = $this->zip->getStream(pathinfo($this->file['name'], PATHINFO_FILENAME) . '.' . TypeToPath($this->modelType));

          $fp = 'zip://' . $this->file['tmp_name'] . '#' . pathinfo($this->file['name'], PATHINFO_FILENAME) . '.' . typeToExtension($this->modelType);
          // fwrite($handle, file_get_contents($fp));
          fwrite($handle, $this->zip->getFromName(pathinfo($this->file['name'], PATHINFO_FILENAME) . '.' . typeToExtension($this->modelType)));
          // fwrite($handle, $fp);
          fclose($handle);
          $this->model['model']['file'] = $this->zip->getFromName(pathinfo($this->file['name'], PATHINFO_FILENAME) . '.' . TypeToPath($this->modelType));
          $this->model['model']['hash'] = md5_file($tempfile);
          $this->fetchInfo($tempfile);
          unlink($tempfile);
          $this->setImage();
        }
      } else if ($this->platform == Model::PLATFORM_QUEST) {
        if ($this->format == Model::FORMAT_BMBF) {
          $this->model['model']['hash'] = md5_file($this->file['tmp_name']);
          $this->readBeatOn();
        }
      }

      $this->zip->close();
    // }
  }

  private function readFromFile() {
    $this->readSettings();
  }
  
  private function fetchInfo($filename) {
    // Place ' 2>&1' in the empty string to get error output
    $windowsFix = "";
    if (ENV == 'local') {
      $windowsFix = "python ";
    }
    exec($windowsFix . ROOT . "/Upload/getInfo.py " . $filename . "", $info);
    // var_dump($info);
    if (empty($info)) {
      FishyUtils::getInstance()->notice("Model info empty");
      return false;
    }
    $this->model['model']['type'] = $info[0];
    $this->model['model']['name'] = $info[1];
    $this->model['model']['author'] = $info[2];
    return true;
  }
  
  private function readBeatOn() {
    $url = WEBROOT . '/';
//    $this->model['format'] = "beatOn";

    $this->model['model']['name'] = $this->beatOn['name'];
    $this->model['model']['author'] = $this->beatOn['author'];
    $this->model['model']['gameVersion'] = $this->beatOn['gameVersion']; //shows the compatible game version
    $this->model['model']['version'] = $this->beatOn['version']; //shows the version of the mod
    if (!empty($this->beatOn['links'])) {
      $this->model['model']['links'] = $this->beatOn['links']; //shows links?
    }

    if (gettype($this->beatOn['description']) == 'array') {
      $lineCount = count($this->beatOn['description']) - 1;
      $description = "";
      foreach ($this->beatOn['description'] as $lineNum => $line) {
        $description .= $line;
        if ($lineCount !== $lineNum) {
          $description .= "<br>";
        }
      }
      $this->model['model']['description'] = $description;
    } else {
      $this->model['model']['description'] = $this->beatOn['description'];
    }


//    $this->model['platform'] = strtolower($this->beatOn['platform']); determined by getModelType
//    $this->model[$key]['quest']['category'] = strtolower($this->beatOn['category']); determined by getModelType
    //validity checking
    if ($this->model['model']['type'] == "saber") {
      $this->model['model']['features']['saberBlade'] = ($this->zip->locateName('SaberBlade.dat') !== false);
      $this->model['model']['features']['saberHandle'] = ($this->zip->locateName('SaberHandle.dat') !== false);
      $this->model['model']['features']['saberGlowingEdges'] = ($this->zip->locateName('SaberGlowingEdges.dat') !== false);
    } else if ($this->model['model']['type'] == "other") {
      $this->model['model']['type'] = 'misc';
    } else {
      failed($url, 'File\'s model type is not valid: ' . $this->model['model']['type']);
      die();
    }

    if (empty($this->model['upload']['platform'])) {
      $valid = false;
      failed($url, 'Platform may not be empty');
      die();
    }
    if (strtolower($this->beatOn['components'][0]['Type']) !== "assetsmod") {
      $valid = false;
      failed($url, 'File is not an asset mod');
      die();
    }
  }
  
  /**
   * Read Settings.
   * Reads data to make the settings.
   */
  protected function readSettings() {
    if ($this->platform == Model::PLATFORM_PC) {
      if ($this->format == Model::FORMAT_UAB) {
        $this->readLicense();
        // simple complexity check
        if ($this->complexity == 'simple') {
          $this->setTags();
          $this->setDescription();
          $this->model['model']['hash'] = md5_file($this->file['tmp_name']);
          $this->model['model']['file'] = $this->file['tmp_name'];
        }
        
      }
    } else {
      //todo
    }
    $this->model['upload']['platform'] = $this->platform;
    $this->model['upload']['format'] = $this->format;
    $this->model['model']['id'] = $this->id;
    //is used to read in all of the functions relating to settings
  }
  
  protected function setModelType() {
    // simple pc check
    if ($this->isAssetBundle()) {
      if ($this->fetchInfo($this->file['tmp_name'])) {
        if (in_array($this->model['model']['type'], TYPE)) {
          $this->platform = Model::PLATFORM_PC;
          $this->format = Model::FORMAT_UAB;
          $this->modelType = $this->model['model']['type'];
          return true;
        }
      }

      return false;
    }
    
    if ($this->isZip()) {
      $this->zip = new ZipArchive();
      if ($this->zip->open($this->file['tmp_name']) !== true) {
        return false;
      }

      // quest bmbf check
      if ($this->zip->locateName('bmbfmod.json') !== false) {
        $this->platform = Model::PLATFORM_QUEST;
        $this->format = Model::FORMAT_BMBF;
        $this->model['upload']['platform'] = $this->platform;
        $this->model['upload']['format'] = $this->format;
        $this->decodeBeatOn();
        $this->model['model']['type'] = strtolower($this->beatOn['category']);
        $this->modelType = $this->model['model']['type'];
        $this->model['model']['file'] = $this->file['tmp_name'];
        return true;
      }

      foreach (TYPE as $filetype) {
        // advanced pc check
        if ($this->zip->locateName(pathinfo($this->file['name'], PATHINFO_FILENAME) . '.' . typeToExtension($filetype)) !== false) {
          $this->platform = Model::PLATFORM_PC;
          $this->format = Model::FORMAT_UAB;
          $this->model['upload']['platform'] = $this->platform;
          $this->model['upload']['format'] = $this->format;
          $this->modelType = $filetype;
          $this->model['model']['type'] = $this->modelType;
          return true;
        }
      }
    }
    return false;
  }

  protected function decodeBeatOn() {
    $this->beatOn = $this->decodeJson('bmbfmod');
  }
  protected function decodeSettings() {
    $this->model = $this->decodeJson('settings');
  }

  protected function decodeJson($filename) {
    $path = 'zip://' . $this->file['tmp_name'] . '#' . $filename . '.json';
    $fp = fopen($path, 'rb');
    $filesize = $this->zip->statName($filename . '.json')['size'];
    $json = fread($fp, $filesize);
// This will remove unwanted characters.
// Check http://www.php.net/chr for details
    for ($i = 0; $i <= 31; ++$i) {
      $json = str_replace(chr($i), "", $json);
    }
    $json = str_replace(chr(127), "", $json);

// This is the most common part
// Some file begins with 'efbbbf' to mark the beginning of the file. (binary level)
// here we detect it and we remove it, basically it's the first 3 characters 
    if (0 === strpos(bin2hex($json), 'efbbbf')) {
      $json = substr($json, 3);
    }
    
    return json_decode($json, true);
  }

  private function isZip() {
    $output = false;

    if (array_search($this->file['type'], ['application/zip', 'application/x-zip-compressed', 'multipart/x-zip']) !== FALSE) {
      $output = true;
    }

    return $output;
  }
  
  private function isAssetBundle() {
    return (array_search($this->file['type'], ['application/octet-stream']) !== FALSE);
  }
  
  protected function readLicense() {
    $output = true;

    if ($this->complexity == 'advanced') {
      $this->license = $this->settings['license'];
      $this->model['license'] = $this->license;
    } else if ($this->complexity == 'simple') {
      $licenseMask['BY'] = (isset($_POST['license']['BY']));
      $licenseMask['SA'] = (isset($_POST['license']['SA']));
      $licenseMask['ND'] = (isset($_POST['license']['ND']));
      $licenseMask['NC'] = (isset($_POST['license']['NC']));
      $licenseMask['NON'] = (isset($_POST['license']['NON']));

      if ($licenseMask['SA'] && $licenseMask['ND'] || count(array_keys($licenseMask, 'false')) == 0) {
        $output = false;
      }

      if ($output) {
        $this->license = $licenseMask;
        $this->model['license'] = $this->license;
      }
    }
    if (!$output) {
      $this->license = ['INVALID'];
      $this->model['license'] = $this->license;
    }

    return $output;
  }

}
