<?php

/**
 * A helper class designed for keeping track of helper functions
 *
 * @author Lauge Rud Knudsen <laugerudknudsen@gmail.com>
 * @version V2
 */
class Helper {

  //properties
  private static $instance;
  /** @var array The array of settings. */
  protected $settings;
  //getters
  //setters
  //methods
  
  function __construct() {
    $settings = file(__DIR__ . '/settings.txt');
    
    array_filter($settings, function($var) {
      $shown = true;
      if (strpos($var, '#') === 0) {
        $shown = false;
      } else if (empty($var)) {
        $shown = false;
      }

      return $shown;
    });
    
    foreach ($settings as $setting) {
      $array = explode('=', $setting);
      $value = isset($array[1]) ? trim($array[1]) : "";
      
      $arrayEnd = strrpos($value, ']');
      if (strpos($value, '[') === 0 && $arrayEnd === (strlen($value) - 1)) {
        $value = substr($value, 1, ($arrayEnd - 1));
        $value = explode(',', $value);
      }
      
      $output[$array[0]] = $value;
    }
    
    $this->settings = $output;
  }
  
  public static function getInstance() {
    if (!isset(self::$instance)) {
      self::$instance = new self;
    }

    return self::$instance;
  }

  private function __clone() { }

  /**
   * Get a settings value.
   * 
   * @param string $key
   * @return mixed Returns false if the key was not found
   */
  public function setting($key) {
    $output = false;

    $key = strtoupper($key);
    if (array_key_exists($key, $this->settings)) {
      $output = $this->settings[$key];
    }

    return $output;
  }
  
  /**
   * Returns the current environment.
   * 
   * @return mixed Returns false if the env setting was not found
   */
  public function env() {
    return $this->setting('ENV');
  }
  
  /**
   * Get the allowed file extensions for upload.
   * 
   * @param string $delimiter The delimiter for seperation. Default value is ","
   * @return string The file extensions seperated by $delimiter
   */
  public function getFileExtensions($delimiter = ',') {
    $extensions = [];
    $extensions = array_merge($extensions, $this->setting('AVATAR_EXTENSIONS'));
    $extensions = array_merge($extensions, $this->setting('SABER_EXTENSIONS'));
    $extensions = array_merge($extensions, $this->setting('PLATFORM_EXTENSIONS'));
    $extensions = array_merge($extensions, $this->setting('NOTE_EXTENSIONS'));
    
    foreach ($extensions as $index => $extension) {
      $extensions[$index] = ".$extension";
    }
    return implode($delimiter, $extensions);
  }
  
  public function convertFromBytes($bytes) {
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    $output['bytes'] = $bytes / pow(1024, $factor);
    $output['type'] = @$sz[$factor];
    return $output;
  }
  
  public function convertToBytes($amount, $byteType = 'MB') {
    switch (strtoupper($byteType)) {
      case 'KB':
        $factor = 1;
        break;
      case 'MB':
        $factor = 2;
        break;
      case 'GB':
        $factor = 3;
        break;
      case 'TB':
        $factor = 4;
        break;
      case 'PB':
        $factor = 5;
        break;
      default:
        $factor = 1;
        break;
    }
    
    $output = $amount * pow(1024, $factor);
    
    return $output;
  }
  
  public function isLocal() {
    return ($this->env() == 'local');
  }
  
  public function isDevelopment() {
    return ($this->env() == 'develop');
  }
  
  public function isProduction() {
    return ($this->env() == 'production');
  }
  
  public function checkRemoteFile($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    // don't download content
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->setting('REMOTE_FILE_TIMEOUT'));

    $output = curl_exec($ch) !== FALSE;
    curl_close($ch);
    return $output;
  }
  
  public function getQuickTags() {
    return file(__DIR__ . '/quicktags.txt');
  }
  
  public function scriptFetcher() {
    echo '<script id="fetcher" src="' . WEBROOT .'/resources/fetcher.js" data-webroot="' . WEBROOT . '"></script>';
  }
  
  public function getCheckmark() {
    include ROOT . '/resources/components/checkmark.php';
  }
  
  public function getXmark() {
    include ROOT . '/resources/components/xmark.php';
  }
  
  public function getMark($isCheckmark = true) {
    if ($isCheckmark) {
      $this->getCheckmark();
    } else {
      $this->getXmark();
    }
  }
  
  public function echoChecked($value) {
    return ($value) ? 'checked' : '';
  }
  
  public function deleteFiles($target) {
    if (is_dir($target)) {
      $files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned

      foreach($files as $file) {
        $this->deleteFiles($file);      
      }

      rmdir($target);
    } elseif(is_file($target)) {
      unlink($target);  
    }
  }
  
}
