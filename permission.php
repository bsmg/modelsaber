<?php

/**
 * A class designed to take care of permissions
 *
 * @author Lauge Rud Knudsen <laugerudknudsen@gmail.com>
 * @version V2
 */
class Permission {
  /** @var int The id of the permission node */
  protected $id;
  /** @var string The name of the permission node */
  protected $name;
  /** @var bool The allowed state of the permission node */
  protected $allowed;
  
  public function getId() {
    return $this->id;
  }

  public function getName() {
    return $this->name;
  }

  public function getAllowed() {
    return $this->allowed;
  }
  
  public function read($roleid, $permissionid) {
    $statement = dbConnection::getInstance()->prepare('SELECT r.permissionid, p.name, r.allowed '
            . 'FROM rolepermissions AS r '
            . 'LEFT JOIN permissions AS p ON r.permissionid = p.permissionid '
            . 'WHERE r.permissionid = :permissionid AND r.roleid = :roleid');
    $statement->bindParam(':permissionid', $permissionid);
    $statement->bindParam(':roleid', $roleid);
    
    $statement->execute();
    
    while ($row = $statement->fetch()) {
      $this->id = $row[0];
      $this->name = $row[1];
      $this->allowed = $row[2];
    }
    
  }
}
