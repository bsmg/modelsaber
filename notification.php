<?php

/**
 * A class designed for easily working with notifications.
 *
 * @author Lauge Rud Knudsen <laugerudknudsen@gmail.com>
 * @version V2
 */
class notification {
  protected $id;
  protected $userid;
  /**
   * Priority level of the notification.
   * A level of "info" is something that doesn't require an action.
   * @var string
   */
  protected $level;
  protected $title;
  protected $message;
  /**
   * Notification is read.
   * Currently unused but can be used in the future in case notifications should be saved instead of deleted.
   * @var null|string
   */
  protected $readAt;
  protected $link = "";
  
  const LEVEL_CRITICAL = 'critical';
  const LEVEL_UPLOADS = 'uploads';
  const LEVEL_FOLLOWED_AUTHORS = 'followed authors';
  const LEVEL_OTHER = 'other';
  
  static function getAllLevels() {
    $levels = [
        'danger' => self::LEVEL_CRITICAL,
        'success' => self::LEVEL_UPLOADS,
        'warning' => self::LEVEL_FOLLOWED_AUTHORS,
        'info' => self::LEVEL_OTHER
    ];
    return $levels;
  }
  public function getId() {
    return $this->id;
  }
  public function getLevel($display = false) {
    if ($display == true) {
      return $this->formatLevelForDisplay();
    }
    return $this->level;
  }
  public function getTitle() {
    return $this->title;
  }
  public function getMessage() {
    return $this->message;
  }
  public function getAge() {
    $creationDate = new DateTime('@' . $this->id);
    $now = new DateTime;
    $diff = $now->diff($creationDate);
    return $this->formatMin($diff);
  }
  public function isRead() {
    return (!empty($this->readAt));
  }
  public function getLink() {
    return $this->link;
  }
  
  public function getPriority($levels) {
    if (array_search('danger', $levels) !== false) {
      $output = 'danger';
    } else if (array_search('warning', $levels) !== false) {
      $output = 'warning';
    } else if (array_search('success', $levels) !== false) {
      $output = 'success';
    } else if (array_search('info', $levels) !== false) {
      $output = 'info';
    } else {
      $output = 'info';
    }
    return $output;
  }
  public function dismiss() {
    $this->update();
  }
  public function dismissAll() {
    $this->updateAll();
  }
  
  public function setId($id) {
    $this->id = $id;
  }
  public function setUserId($userId) {
    $this->userid = $userId;
  }
  public function setLevel($level) {
    switch (strtolower($level)) {
      case self::LEVEL_CRITICAL:
      case 'danger':
        $level = 'danger';
        break;
      case self::LEVEL_UPLOADS:
      case 'warning':
        $level = 'warning';
        break;
      case self::LEVEL_FOLLOWED_AUTHORS:
      case 'success':
        $level = 'success';
        break;
      case self::LEVEL_OTHER:
      case 'info':
        $level = 'info';
        break;
    }
    $this->level = $level;
  }
  public function setTitle($title) {
    $this->title = $title;
  }
  public function setMessage($message) {
    $this->message = $message;
  }
  public function setLink($link) {
    $this->link = $link;
  }
  
  function __construct($userId = null) {
    if (!empty($userId)) {
      $this->setUserId($userId);
    }
  }
  
  public function create($title,  $level = "info", $message = "") {
    $this->id = time();
    $this->title = $title;
    $this->level = $level;
    $this->message = $message;
    
    $statement = dbConnection::getInstance()->prepare('INSERT INTO notifications (notificationid, level, title, message, link) '
            . "VALUES (:id, :level, :title, NULLIF(:message,'')::text, :link)");
    $statement->bindParam(':id', $this->id);
    $statement->bindParam(':level', $this->level);
    $statement->bindParam(':title', $this->title);
    $statement->bindParam(':message', $this->message);
    $statement->bindParam(':link', $this->link);
    
    $statement->execute();
    
    if (!empty($this->userid)) {
      /*
      $this->userid = $this->userid;
      $statement = dbConnection::getInstance()->prepare('INSERT INTO notificationusers (notificationid, userid) '
              . 'VALUES (:id, CAST(:userid AS bigint))');
      $statement->bindParam(':id', $this->id);
      $statement->bindParam(':userid', $this->userid);
      
      $statement->execute();
       * 
       */
      $this->addRelations($this->userid);
    }
    return true;
  }
  public function addRelations($userids) {
    if (!is_array($userids)) {
      $userids = [$userids];
    }
    foreach ($userids as $userid) {
      $statement = dbConnection::getInstance()->prepare('INSERT INTO notificationusers (notificationid, userid) '
              . 'VALUES (:id, :userid)');
      $statement->bindParam(':id', $this->id);
      $statement->bindParam(':userid', $userid);
      
      $statement->execute();
    }
  }
  
  public function read() {
    $output = false;
    
    $statement = dbConnection::getInstance()->prepare('SELECT n.notificationid, n.level, n.title, n.message, n.readat, n.link '
            . 'FROM notifications AS n '
            . 'LEFT JOIN notificationusers AS u ON n.notificationid = u.notificationid '
            . 'WHERE u.userid = :userid AND n.readat IS NULL');
    $statement->bindParam(':userid', $this->userid);
    
    $statement->execute();
    
    while ($row = $statement->fetch()) {
      $tempNotification = new notification($this->userid);
      $tempNotification->setId($row['notificationid']);
      $tempNotification->setLevel($row['level']);
      $tempNotification->setTitle($row['title']);
      $tempNotification->setMessage($row['message']);
      $tempNotification->setLink($row['link']);
      if (!empty($row['readat'])) {
        $tempNotification->readAt = $row['readat'];
      }
      $output[] = $tempNotification;
      unset($tempNotification);
    }
    if (!empty($output)) {
      usort($output, array($this, "sortById"));
    }
    
    return $output;
  }
  public function readFromId($id) {
     $this->id = $id;
     
     $statement = dbConnection::getInstance()->prepare('SELECT n.notificationid, n.level, n.title, n.message, n.readat, n.link '
             . 'FROM notifications AS n '
             . 'LEFT JOIN notificationusers AS u ON n.notificationid = u.notificationid '
             . 'WHERE u.userid = :userid AND n.notificationid = :id');
     $statement->bindParam(':userid', $this->userid);
     $statement->bindParam(':id', $this->id);
     
    $statement->execute();
    
    while ($row = $statement->fetch()) {
      $this->setId($row['notificationid']);
      $this->setLevel($row['level']);
      $this->setTitle($row['title']);
      $this->setMessage($row['message']);
      $this->setLink($row['link']);
      if (!empty($row['readat'])) {
        $this->readAt = $row['readat'];
      }
    }
      
  }
  public function readFromLevel($level) {
    if ($level == 'all') {
      $this->level = '%';
    } else {
      $this->setLevel($level);
    }
    
    $statement = dbConnection::getInstance()->prepare('SELECT n.notificationid, n.level, n.title, n.message, n.readat, n.link '
            . 'FROM notifications AS n '
            . 'LEFT JOIN notificationusers AS u ON n.notificationid = u.notificationid '
            . 'WHERE u.userid = :userid AND n.level LIKE :level AND n.readat IS NULL');
    $statement->bindParam(':userid', $this->userid);
    $statement->bindParam(':level', $this->level);
    
    $statement->execute();
    
    while ($row = $statement->fetch()) {
      $tempNotification = new notification($this->userid);
      $tempNotification->setId($row['notificationid']);
      $tempNotification->setLevel($row['level']);
      $tempNotification->setTitle($row['title']);
      $tempNotification->setMessage($row['message']);
      $tempNotification->setLink($row['link']);
      if (!empty($row['readat'])) {
        $tempNotification->readAt = $row['readat'];
      }
      $output[] = $tempNotification;
      unset($tempNotification);
    }
    if (!empty($output)) {
      usort($output, array($this, "sortById"));
    }
    
    return $output;
  }
  protected function update() {
    $statement = dbConnection::getInstance()->prepare('UPDATE notifications '
            . 'SET readat = :time '
            . 'WHERE notificationid = :id');
    $statement->bindParam(':id', $this->id);
    $statement->bindValue(':time', time());
    
    $statement->execute();
  }
  protected function updateAll() {
    $statement = dbConnection::getInstance()->prepare('UPDATE notifications AS n '
            . 'SET readat = :time '
            . 'FROM notificationusers AS u '
            . 'WHERE n.notificationid = u.notificationid AND u.userid = :userid');
    $statement->bindParam(':userid', $this->userid);
    $statement->bindValue(':time', time());
    
    $statement->execute();
  }
  public function delete() {
    //todo
  }
  
  protected function formatMin($diff) {
    if ($diff->y !== 0) {
      $output = $diff->y . " years ago";
    } else if ($diff->m !== 0) {
      $output = $diff->m . " months ago";
    } else if ($diff->d !== 0) {
      $output = $diff->d . " days ago";
    } else if ($diff->h !== 0) {
      $output = $diff->h . " hours ago";
    } else if ($diff->i !== 0) {
      $output = $diff->i . " minutes ago";
    } else {
      $output = "just now";
    }
    return $output;
  }
  
  protected function sortById($a, $b) {
    return ($a->id < $b->id);
  }
  protected function formatLevelForDisplay() {
    switch ($this->level) {
      case 'danger':
        $output = self::LEVEL_CRITICAL;
        break;
      case 'warning':
        $output = self::LEVEL_UPLOADS;
        break;
      case 'success':
        $output = self::LEVEL_FOLLOWED_AUTHORS;
        break;
      case 'info':
      default:
        $output = self::LEVEL_OTHER;
        break;
    }
    return $output;
  }

}
