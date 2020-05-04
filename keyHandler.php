<?php
/**
 * A class designed to take care of handling API keys.
 *
 * @author Lauge Rud Knudsen <laugerudknudsen@gmail.com>
 * @version V2
 */
class KeyHandler {
  // Properties
  private $key;
  private $userid;
  
  // Permissions
  private $canVote = false;
  private $canManage = false;
  
  // Constants
  const KEY_LENGTH = 16;
  const KEY_CHARSET = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  const PERMISSION_VOTE_DESCRIPTION = 'Determines if the key allows voting on your behalf.';
  const PERMISSION_MANAGE_DESCRIPTION = 'Determines if the key allows approving or declining models on your behalf.';
  
  // Getters
  public function getKey() {
    return $this->key;
  }
  
  public function getCanVote() {
    return $this->canVote;
  }
  
  public function getCanManage() {
    return $this->canManage;
  }
  public function getUserid() {
    return $this->userid;
  }
  
  public function getPermissions() {
    global $currentUser;
    $output['Vote'] = [$this->canVote, self::PERMISSION_VOTE_DESCRIPTION];
    
    if ($currentUser->canApprove()) {
      $output['Manage'] = [$this->canManage, self::PERMISSION_MANAGE_DESCRIPTION];
    }
    return $output;
  }
  
  public static function getAllPermissions() {
    global $currentUser;
    $output[] = ['Vote', self::PERMISSION_VOTE_DESCRIPTION];
    
    if ($currentUser->canApprove()) {
      $output[] = ['Manage', self::PERMISSION_MANAGE_DESCRIPTION];
    }
    return $output;
  }
  
  public static function getKeyCount($userId) {
    $output = 0;
    
    $statement = dbConnection::getInstance()->prepare('SELECT COUNT(key) AS keycount '
            . 'FROM apikeys '
            . 'WHERE userid = :userid');
    $statement->bindParam(':userid', $userId);

    $statement->execute();
    while ($row = $statement->fetch()) {
      $output = $row['keycount'];
    }
    
    $output = (empty($output)) ? 0 : $output;
    
    return $output;
  }
  
  // Setters
  public function setUserId($userid) {
    $this->userid = (string)$userid;
  }
  
  public function setCanVote($canVote) {
    $this->canVote = $canVote;
  }
  
  public function setCanManage($canManage) {
    global $currentUser;
    
    if ($currentUser->canApprove()) {
      $this->canManage = $canManage;
    }
  }
  
  // Public Methods
  
  public function create() {
    if (!$this->validate()) {
      return;
    }
    
    $this->key = $this->generateNewKey();
    
    $statement = dbConnection::getInstance()->prepare('INSERT INTO apikeys (userid, key, canvote, canmanage) '
            . 'VALUES (CAST(:userid AS bigint), :key, :canvote, :canmanage)');
    $statement->bindParam(':userid', $this->userid);
    $statement->bindParam(':key', $this->key);
    $statement->bindParam(':canvote', $this->canVote, PDO::PARAM_BOOL);
    $statement->bindParam(':canmanage', $this->canManage, PDO::PARAM_BOOL);
    
    $statement->execute();
  }
  
  public function read($key) {
    $statement = dbConnection::getInstance()->prepare('SELECT userid, canvote, canmanage '
            . 'FROM apikeys '
            . 'WHERE key = :key');
    $statement->bindParam(':key', $key);

    $statement->execute();
    while ($row = $statement->fetch()) {
      $this->key = $key;
      $this->userid = $row['userid'];
      $this->canVote = $row['canvote'];
      $this->canManage = $row['canmanage'];
    }
  }
  
  public static function readFromUser($userId) {
    if (!self::validateUserId($userId)) {
      return;
    }
    
    $statement = dbConnection::getInstance()->prepare('SELECT key '
            . 'FROM apikeys '
            . 'WHERE userid = :userid');
    $statement->bindParam(':userid', $userId);

    $statement->execute();
    while ($row = $statement->fetch()) {
      $temp = new KeyHandler();
      $temp->read($row['key']);
      $output[] = $temp;
      unset($temp);
    }
    
    if (!isset($output)) {
      $output = [];
    }
    
    return $output;
  }
  
  public function update($regenerate = false) {
    if (!$this->validate()) {
      return;
    }
    
    if ($regenerate) {
      $key = $this->generateNewKey();
    } else {
      $key = $this->key;
    }
    
    $statement = dbConnection::getInstance()->prepare('UPDATE apikeys '
            . 'SET key = :key, canvote = :canvote, canmanage = :canmanage '
            . 'WHERE key = :oldkey');
    $statement->bindParam(':key', $key);
    $statement->bindParam(':oldkey', $this->key);
    $statement->bindParam(':canvote', $this->canVote, PDO::PARAM_BOOL);
    $statement->bindParam(':canmanage', $this->canManage, PDO::PARAM_BOOL);
    
    $statement->execute();
  }
  
  public function delete() {
    $statement = dbConnection::getInstance()->prepare('DELETE FROM apikeys '
            . 'WHERE key = :key');
    $statement->bindParam(':key', $this->key);
    
    $statement->execute();
  }
  
  //Private Methods
  
  /**
   * Generates a random API key.
   * 
   * @return string The randomized key.
   * @throws \RangeException when length is less than 1.
   */
  private function generate() {
    if (self::KEY_LENGTH < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    
    $pieces = [];
    $max = mb_strlen(self::KEY_CHARSET, '8bit') - 1;
    for ($i = 0; $i < self::KEY_LENGTH; ++$i) {
        $pieces[] = self::KEY_CHARSET[random_int(0, $max)];
    }
    
    return implode('', $pieces);
  }
  
  /**
   * Validates properties.
   * 
   * @return boolean Returns true if validation suceeded
   */
  private function validate() {
    $output = TRUE;
    
    if (!is_bool($this->canVote)) {
      $output = FALSE;
    }
    if (!is_bool($this->canManage)) {
      $output = FALSE;
    }
    if (!self::validateUserId($this->userid)) {
      $output = FALSE;
    }
    if (!self::getKeyCount($this->userid) <= Helper::getInstance()->setting('MAX_API_KEYS')) {
      $output = FALSE;
    }
    
    return $output;
  }
  
  private static function validateUserId($userId) {
    $output = TRUE;
    
    if (!User::exists($userId)) {
      $output = FALSE;
    }
    
    return $output;
  }
  
  /**
   * Generates a new valid key.
   * 
   * @return string A valid key.
   */
  private function generateNewKey() {
    $keyExists = true;
    while ($keyExists) {
      $key = $this->generate();
      $keyExists = self::exists($key);
    }
    
    return $key;
  }
  
  /**
   * Check if a given key exists.
   * 
   * @param string $key The key to search for.
   * @return bool True if the key was found, false otherwise.
   */
  private static function exists($key) {
    $output = FALSE;
    
    $statement = dbConnection::getInstance()->prepare('SELECT EXISTS '
            . '(SELECT 1 FROM apikeys WHERE key = :key)');
    $statement->bindParam(':key', $key);

    $statement->execute();
    while ($row = $statement->fetch()) {
      $output = $row[0];
    }
    
    return $output;
  }
}
