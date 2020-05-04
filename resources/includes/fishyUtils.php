<?php

/** 
 * Fishy Utils.
 * Fishy Utils is a class with some useful functions.
 * 
 * @author Lauge Rud Knudsen <laugerudknudsen@gmail.com>
 * @version 0.1.0
 */
class FishyUtils {
  private static $instance;
  
  private $previousMode;
  /**
   * Contains all logged errors.
   * Contains all the logged errors in the format ['level', 'message'].
   * 
   * @var string[]
   * @since 0.1.0
   */
  protected $errors;
  /**
   * Contains the error logging mode.
   * Available options are:
   * - <b>file</b> Keeps the error logging in a log file {@see FishyUtils::$logPath}.
   * - <b>console</b> Prints the errors to the console.
   * - <b>print</b> Prints the errors to the screen.
   * 
   * @var string
   * @since 0.1.0
   */
  protected $errorMode = FISHY_ERROR_MODE;
  /**
   * Contains the path to the log file.
   * Only usefull when {@see FishyUtils::$errorMode} is set to 'file'.
   * 
   * @var string
   * @since 0.1.0
   */
  protected $logPath = FISHY_LOG_PATH;
  
  public function getErrorMode() {
    return $this->errorMode;
  }
  
  public static function getInstance()  {
        if ( !isset(self::$instance) ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __clone() { }
  
  public function errorHandler($errorLevel, $errorMessage, $errorFile = '', $errorLine = '') {
    switch ($errorLevel) {
        case E_ERROR:
        case E_USER_ERROR:
          $level = 'error';
          break;
        case E_WARNING:
        case E_USER_WARNING:
          $level = 'warning';
          break;
        case E_NOTICE:
        case E_USER_NOTICE:
          $level = 'notice';
          break;
        case 'log':
          $level = 'log';
          break;
        default:
          $level = 'unknown-error';
          break;
      }
      if (gettype($errorMessage) == 'array' || gettype($errorMessage) == 'object') {
        $message = json_encode($errorMessage, JSON_UNESCAPED_SLASHES);
      } else {
        $message = addslashes($errorMessage);
      }
      $file = (!empty($errorFile)) ? $errorFile : 'unknown file';
      $line = (!empty($errorLine)) ? $errorLine : 'unknown line';
    $error = [
        'level' => $level,
        'message' => $message,
        'file' => $file,
        'line' => $line
            ];
    $this->errors[] = $error;
    switch ($this->getErrorMode()) {
      case 'file':
        $this->fileError($error);
        break;
      case 'console':
        $this->consoleError($error);
        break;
      case 'print':
        $this->printError($error);
        break;
    }
  }

  public function error($message) {
    $this->errorHandler(E_USER_ERROR, $message);
  }
  public function warning($message) {
    $this->errorHandler(E_USER_WARNING, $message);
  }
  public function notice($message) {
    $this->errorHandler(E_USER_NOTICE, $message);
  }
  public function log($message) {
    $this->errorHandler('log', $message);
  }
  public function debug() {
    $this->notice('DEBUG AT');
  }

  public function hasErrors() {
    $output = false;
    
    if (!empty($this->errors)) {
      $levels = array_column($this->errors, 'level');
      if (count(array_keys($levels, [E_ERROR, E_USER_ERROR])) > 0) {
        $output = true;
      }
    }
    
    return $output;
  }
  protected function fileError($error) {
    if ($error['level'] !== 'log') {
      $escapedError = '[' . date('Y-m-d H:i:s', time()) . '] ' . addslashes($error['level'] . ': ' . $error['message']) . ' [at] ' . $error['file'] . ' [on line] ' . $error['line'] . "\n";
      file_put_contents($this->logPath, $escapedError, FILE_APPEND);
    }
  }
  protected function printError($error) {
    echo "<span class='fishy-" . $error['level'] . "'><strong>" . ucfirst($error['level']) . ":</strong> " . $error['message'] . "</span>";
  }
  protected function consoleError($error) {
    $text = ucfirst($error['level']) . ': ' 
            . $error['message'];
            /*$getout = (!empty($error['file']))? ' File: ' . $error['file'] : '' 
      . (($error['line']) !== 0)? ' Line: ' . $error['line']: '';*/
    if ($error['level'] == 'error' || $error['level'] == 'unknown-error') {
      echo "<script>console.error('$text');</script>";
    } else if ($error['level'] == 'warning') {
      echo "<script>console.warn('$text');</script>";
    } else if ($error['level'] == 'notice') {
      echo "<script>console.info('$text');</script>";
    } else {
      echo "<script>console.log('$text');</script>";
    }
    
  }
  
  /**
   * Disables error logging.
   * 
   * @since 0.1.0
   */
  public function disableLogging() {
    $this->previousMode = $this->errorMode;
    $this->errorMode = 'none';
  }
  
  /**
   * Enables error logging.
   * 
   * @since 0.1.0
   */
  public function enableLogging() {
    $this->errorMode = $this->previousMode;
    $this->previousMode = 'none';
  }
  
  /**
   * Logs errors from exec().
   * 
   * @param string[] $errorArray The output from exec()
   * @since 0.1.0
   */
  public function logExecError($errorArray) {
    if (!empty($errorArray)) {
      $this->error($errorArray[0]);
    }
  }

}
