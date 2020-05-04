<?php

/**
 * A singleton designed to hold the database connection.
 *
 * @author Lauge Rud Knudsen <laugerudknudsen@gmail.com>
 * @version V2
 */
class dbConnection {

  private static $instance;
  private $driver = 'pgsql';
  private $user;
  private $password;
  private $host = "127.0.0.1";
  private $port = "5432";
  private $dbname;
  private $persistent = false;
  private $db;

  function __construct() {
    $helper = Helper::getInstance();
    $this->user = $helper->setting('DATABASE_USER');
    $this->password = $helper->setting('DATABASE_PASSWORD');
    $this->dbname = $helper->setting('DATABASE_NAME');
    $this->driver = $helper->setting('DATABASE_DRIVER');
    $this->persistent = $helper->setting('DATABASE_PERSISTENT');
  }
  public static function getInstance() {
    if (!isset(self::$instance)) {
      self::$instance = new self;
    }

    return self::$instance;
  }

  private function __clone() { }
  
  public function getDB() {
    return $this->db;
  }

  public function start() {
    /*
    $credentials = "user = " . Helper::getInstance()->setting('DATABASE_USER') . " password = " . Helper::getInstance()->setting('DATABASE_PASSWORD');
    $this->db = pg_connect("$this->host $this->port dbname = " . Helper::getInstance()->setting('DATABASE_NAME') . " $credentials");
    unset($credentials);
     */
    try {
      $this->db = new PDO($this->driver . ':host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->dbname, $this->user, $this->password, [
          PDO::ATTR_PERSISTENT => $this->persistent
              ]);
    } catch (PDOException $ex) {
      die();
    }
  }
  
  public function close() {
    $this->db = null;
//    pg_close($this->db);
  }
  
  public function prepare($query) {
    return $this->db->prepare($query);
  }

}
