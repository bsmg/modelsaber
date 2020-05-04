<?php

/**
 * A class designed to take care of showing the log file.
 *
 * @author Lauge Rud Knudsen <laugerudknudsen@gmail.com>
 * @version V2
 */
class Log {
  protected $log;
  protected $datetimeFilterFrom;
  protected $datetimeFilterTo;
  protected $levelFilter = [];
  
  //constants
  const UNKNOWN_DATETIME_MESSAGE = 'Unknown Date and time';
  const UNKNOWN_LEVEL_MESSAGE = 'Unknown error level';
  const EMPTY_ERROR_MESSAGE = 'Message was empty';
  const EMPTY_FILE_MESSAGE = 'File was empty';
  const EMPTY_LINE_MESSAGE = 'Line was empty';
  const DATETIME_FORMAT = 'Y-m-d H:i:s';
  
  //getters
  /**
   * Get all filtered levels.
   * 
   * @return string[] An array containing all filtered levels.
   */
  public function getLevels() {
    return $this->levelFilter;
  }
  /**
   * Get the datetimeFrom filter value.
   * 
   * @return datetime The date and time.
   */
  public function getDatetimeFrom() {
    return $this->datetimeFilterFrom;
  }
  
  /**
   * Get the datetimeTo filter value.
   * 
   * @return datetime The date and time.
   */
  public function getDatetimeTo() {
    return $this->datetimeFilterTo;
  }
  /**
   * Get all valid level values.
   * 
   * @return string[] An array containing all possible level values.
   */
  public function getAllLevels() {
    return ['notice', 'warning', 'error'];
  }
  
  //setters
  /**
   * Sets the level filters.
   * 
   * @param string[] $levels The levels that should be filtered.
   */
  public function setFilterLevels($levels = []) {
    $output = [];
    
    foreach ($this->getAllLevels() as $level) {
      if (array_search($level, $levels) !== FALSE) {
        $output[] = $level;
      }
    }
    
    $this->levelFilter = $output;
  }
  
  /**
   * Sets the date from filter.
   * 
   * @param string $date The date and time as a string.
   */
  public function setFilterDateFrom($date) {
    $this->datetimeFilterFrom = DateTime::createFromFormat(self::DATETIME_FORMAT, $date);
  }
  
  /**
   * Sets the date to filter.
   * 
   * @param string $date The date and time as a string.
   */
  public function setFilterDateTo($date) {
    $this->datetimeFilterTo = DateTime::createFromFormat(self::DATETIME_FORMAT, $date);
  }
  //public functions
  
  function __construct() {
    if (file_exists(FISHY_LOG_PATH)) {
      $this->log = file(FISHY_LOG_PATH);
    }
    
  }
  
  /**
   * Reads in the log and returns it.
   * 
   * @return array[]|false An array containing each row or false if the log is invalid.
   */
  public function read() {
    if (!empty($this->log)) {
      if (empty($this->datetimeFilterFrom)) {
        $this->resetDateFromFilter();
      }
      if (empty($this->datetimeFilterTo)) {
        $this->setFilterDateTo(date(self::DATETIME_FORMAT));
      }
      
      foreach ($this->log as $line) {
        $row = $this->toRow($line);
        if (!empty($row)) {
          $row['color'] = $this->getColor($line);
          $output[] = $row;
        }
      }
    }
    
    if (!isset($output)) {
      $output = false;
    }
    
    return $output;
  }
  
  /**
   * Check if a desired level is filtered.
   * 
   * @param string $level The level to be searched.
   * @return bool Whether or not the level is filtered.
   */
  public function hasLevelFilter($level) {
    return (array_search($level, $this->levelFilter) !== FALSE);
  }
  
  /**
   * Sets the dateFromFilter to the unix epoch.
   */
  public function resetDateFromFilter() {
    $this->datetimeFilterFrom = (new DateTime())->setTimestamp(0);
  }
  //private functions
  
  /**
   * Gets the color of a specified line.
   * 
   * @param string $line The line in question as a string.
   * @return string|false A string containing the hex code for the error level.
   */
  protected function getColor($line) {
    $output = false;
    
    switch ($this->getLevel($line)) {
      case 'notice':
        $output = '#0000ff';
        break;
      case 'warning':
        $output = '#ffff00';
        break;
      case 'error':
        $output = '#ff0000';
        break;
      default:
        $output = '#ffffff';
        break;
    }
    
    return $output;
  }
  
  /**
   * Convert the specified line to a table row.
   * 
   * @param string $line The line in question as a string.
   */
  protected function toRow($line) {
    $level = $this->getLevel($line);
    if ($this->hasLevelFilter($level)) {
      return null;
    }
    
    $datetimeStart = strpos($line, '[');
    $datetimeEnd = strpos($line, ']');
    if ($datetimeStart === 0 && $datetimeEnd > 0) {
      $datetime = DateTime::createFromFormat(self::DATETIME_FORMAT, substr($line, $datetimeStart + 1, $datetimeEnd - 1));
    }
    
    if (isset($datetime) < $this->datetimeFilterFrom || $datetime > $this->datetimeFilterTo) {
      return null;
    }
    
    $messageStart = strpos($line, ':', $datetimeEnd);
    $fileStart = strpos($line, '[at] ', $messageStart);
    if ($fileStart === FALSE) {
      $message = substr($line, $messageStart + 2);
    } else {
      $message = substr($line, $messageStart + 2, $fileStart - $datetimeEnd - 7);
    }
        
    $lineStart = strpos($line, '[on line] ');
    if ($fileStart === FALSE) {
      $file = '';
    } else {
      $file = substr($line, $fileStart + 5, $lineStart - $fileStart - 6);
    }
    
    if ($lineStart === FALSE) {
      $lineNum = '';
    } else {
      $lineNum = substr($line, $lineStart + 10);
    }
    
    $output['datetime'] = (isset($datetime)) ? addslashes($datetime->format(self::DATETIME_FORMAT)) : self::UNKNOWN_DATETIME_MESSAGE;
    $output['level'] = ($level !== FALSE) ? addslashes($level) : self::UNKNOWN_LEVEL_MESSAGE;
    $output['message'] = (isset($message)) ? addslashes($message) : self::EMPTY_ERROR_MESSAGE;
    $output['file'] = (isset($file)) ? addslashes($file) : self::EMPTY_FILE_MESSAGE;
    $output['line'] = (isset($lineNum)) ? addslashes($lineNum) : self::EMPTY_LINE_MESSAGE;
    
    return $output;
  }
  
  /**
   * Get the level of the specified line.
   * 
   * @param string $line The line in question as a string.
   * @return string|false Returns the string name of the level, false if it wasn't found.
   */
  protected function getLevel($line) {
    $output = false;
    
    foreach ($this->getAllLevels() as $level) {
      $pos = strpos($line, "] $level:");
      if ($pos !== FALSE) {
        $output = $level;
      }
    }
    /*
    $log = strpos($line, '] log:');
    $notice = strpos($line, '] notice:');
    $warning = strpos($line, '] warning:');
    $error = strpos($line, '] error:');
    
    if ($log !== FALSE) {
      $output = 'log';
    } else if ($notice !== FALSE) {
      $output = 'notice';
    } else if ($warning !== FALSE) {
      $output = 'warning';
    } else if ($error !== FALSE) {
      $output = 'error';
    }
    */
    
    return $output;
  }
}
