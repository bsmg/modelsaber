<?php

/**
 * A class designed to take care of roles
 *
 * @author Lauge Rud Knudsen <laugerudknudsen@gmail.com>
 * @version V2
 */
class Role {
  /** @var string The unique id of this role */
  protected $id;
  /** @var string The text shown when hovering over the icon */
  protected $title;
  /** @var string The name of the image holding the icon */
  protected $image;
  /** @var int The display priority */
  protected $priority;
  /** @var string The 6 character hex color code for comments */
  protected $color;
  /** @var string The title displayed in comments */
  protected $chatTitle;
  /** @var string[] An array containing all the permission nodes of this role */
  protected $permissions;
  
  //getters
  public function getId() {
    return $this->id;
  }
  public function getTitle() {
    return $this->title;
  }
  public function getImage() {
    if (empty($this->image)) {
      return false;
    }
    return WEBROOT . '/resources/roles/' . $this->image;
  }
  public function getPriority() {
    return $this->priority;
  }
  public function getColor() {
    return $this->color;
  }
  public function getChatTitle() {
    return $this->chatTitle;
  }
  
  //setters
  public function setId($id) {
    $this->id = $id;
  }
  
  public function create() {
    $output = false;
    
    $statement = dbConnection::getInstance()->prepare('INSERT INTO roles (roleid) '
            . 'VALUES (:id)');
    $statement->bindParam(':id', $this->id);
    
    $output = $statement->execute();
    
    return $output;
  }
  
  static function readAll() {
    $statement = dbConnection::getInstance()->prepare('SELECT roleid, title, image, priority, color, chattitle '
            . 'FROM roles');
    
    $statement->execute();
    
    while ($row = $statement->fetch()) {
      $role = new Role();
      $role->setId($row['roleid']);
      $role->read();
      
      $output[] = $role;
    }
    return $output;
  }
  
  //public functions
  public function read() {    
    $statement = dbConnection::getInstance()->prepare('SELECT title, image, priority, color, chattitle '
            . 'FROM roles '
            . 'WHERE roleid = :id');
    $statement->bindParam(':id', $this->id);
    
    $statement->execute();
    
    while ($row = $statement->fetch()) {
      $this->title = $row['title'];
      $this->image = $row['image'];
      $this->priority = $row['priority'];
      $this->color = $row['color'];
      $this->chatTitle = $row['chattitle'];
    }
      
    $this->readPermissions();

    if (empty($this->priority)) {
      $this->priority = 0;
    }
  }
  
  protected function readPermissions() {
    $statement = dbConnection::getInstance()->prepare('SELECT permissionid '
            . 'FROM rolepermissions '
            . 'WHERE roleid = :id');
    $statement->bindParam(':id', $this->id);
    
    $statement->execute();
    
    while ($row = $statement->fetch()) {
      $permissionIds[] = $row[0];
    }
    
    if (isset($permissionIds)) {
      foreach ($permissionIds as $permissionid) {
        $permission = new Permission();
        $permission->read($this->id, $permissionid);
        $output[$permission->getName()] = $permission;
      }
      $this->permissions = $output;
    }
  }
  
  public function hasPermission_old($permission) {
    if (empty($this->permissions)) {
      $output = false;
    } else {
      $output = (array_search($permission, $this->permissions) !== FALSE);
    }
    return $output;
  }
  
  public function hasPermission($permission) {
    if (empty($this->permissions)) {
      $output = null;
    } else {
      if (array_key_exists($permission, $this->permissions)) {
        $output = $this->permissions[$permission]->getAllowed();
      } else {
        $output = null;
      }
      
    }
    return $output;
  }
  
  //private functions
}
