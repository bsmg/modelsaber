<?php

/**
 * User accounts.
 * 
 * Designed to make users easily accessible and useful.
 *
 * @author Lauge Rud Knudsen <laugerudknudsen@gmail.com>
 * @since V2
 */
class User {
  protected $discordId;
  protected $roles;
  protected $description;
  protected $username;
  protected $discriminator;
  protected $isLoggedIn = false;
  protected $bsaber;
  protected $avatar;
  
  //Getters
  public function getDiscordId() {
    return $this->discordId;
  }
  public function getDescription() {
    return $this->description;
  }
  public function getUsername() {
    return $this->username;
  }
  public function getDiscriminator() {
    return $this->discriminator;
  }
  public function isLoggedIn() {
    return $this->isLoggedIn;
  }
  public function getRoles() {
    return $this->roles;
  }
  public function getBsaber($clean = false) {
    $output = FALSE;
    
    if (!empty($this->bsaber)) {
      if ($clean) {
        $output = $this->bsaber;
      } else {
        $output = 'https://bsaber.com/members/' . $this->bsaber;
      }
    }

    return $output;
  }
  public function getVerified() {
    return $this->hasRole('verified');
  }
  public function isVerified() {
    if (!$this->isLoggedIn) {
      return false;
    }
    return $this->hasRole('verified');
  }
  
  public function getUserTags() {
    if ($this->isAdmin()) {
      $tags[] = ROOT . "/resources/components/adminUserTag.php";
    }
    if ($this->isBSMGStaff()) {
      $tags[] = ROOT . "/resources/components/BSMGUserTag.php";
    }
    if (isset($tags)) {
      return $tags;
    } else {
      return false;
    }
  }
  public function getAvatar() {
    if (strpos($this->avatar, 'a_') === FALSE) {
      return 'https://cdn.discordapp.com/avatars/' . $this->discordId . '/' . $this->avatar . '.png';
    } else {
      return 'https://cdn.discordapp.com/avatars/' . $this->discordId . '/' . $this->avatar . '.gif';
    }
  }
  public function getDefaultAvatar() {
    return 'https://cdn.discordapp.com/embed/avatars/' . $this->discriminator % 5 . '.png';
  }
  /**
   * Bobbyfies a text.
   * 
   * @param string $text The string to bobbyfy
   * @return string The bobbyfied string
   */
  public function bobbyfy($text) {
    return str_replace('ie', 'y', $text);
  }
  
  /**
   * Gets the role icon.
   * 
   * Gets the highest priority role with an image and returns an array with the image and title.
   * 
   * @return bool|string[] Returns an array with the icon on success, returns false otherwise.
   */
  public function getIcon() {
    $iconKey = $this->findRoleImage();
    $output = false;
    if ($iconKey !== false) {
      $output = [
          'image' => $this->roles[$iconKey]->getImage(),
          'title' => $this->roles[$iconKey]->getTitle()
      ];
    }
    return $output;
  }
  /**
   * Gets the image for the name.
   * 
   * @return string The absolute path to the image from the webroot
   * @deprecated {@see User::getIcon()}
   */
  public function getNameImage() {
    return $this->findRoleImage();
  }
  /**
   * 
   * @return string
   * @deprecated {@see User::getIcon()}
   */
  public function getNameImageTitle() {
    $output = '';
    if ($this->isGameDeveloper()) {
      $output = 'BeatSaber Developer';
    } else if ($this->isModelsaberDeveloper()) {
      $output = SITECAMEL . ' Developer';
    } else if ($this->isBobby()) {
      $output = "You've been gnomed";
    } else if ($this->isJoelseph()) {
      $output = 'Joelseph';
    }
    return $output;
  }
  
  /**
   * Gets the class for the name color.
   * 
   * @return string The class name of the color
   */
  public function getNameColor() {
    $output = "";
    if ($this->isGay()) {
      $output = 'has-text-gay';
    }
    return $output;
  }
  
  //Methods
  public function create() {
    FishyUtils::getInstance()->log("Creating user");
    
    $this->discriminator = session('discord_user')->discriminator;
    $this->username = session('discord_user')->username;
    $this->discordId = session('discord_user')->id;
    $this->avatar = session('discord_user')->avatar;
//    $this->description = pg_escape_literal(strip_tags($description, '<b><br><hr><strong><em>'));
    if (empty($this->discordId)) {
      trigger_error('Variable $this->discordId is empty', E_USER_ERROR);
    } else if ($this->exists(session('discord_user')->id)) {
      trigger_error('Object User with a discordId of ' . session('discord_user')->id . ' already exists', E_USER_ERROR);
    }
    if (FishyUtils::getInstance()->hasErrors()) {
      return false;
    }
    $statement = dbConnection::getInstance()->prepare('INSERT INTO users (discordid, username, discriminator, avatar) '
            . 'VALUES (:discordid, :username, :discriminator, :avatar) '
            . 'RETURNING discordid');
    $statement->bindParam(':discordid', $this->discordId);
    $statement->bindParam(':username', $this->username);
    $statement->bindParam(':discriminator', $this->discriminator);
    $statement->bindParam(':avatar', $this->avatar);
    
    $output = $statement->execute();
    
    FishyUtils::getInstance()->log("Finished creating user. Output: " . $output);
    return $output;
  }
  
  public function read($discordId) {
//    FishyUtils::getInstance()->log("Reading user with parameters: $discordId");
    $output = false;
    
    if (empty($discordId)) {
      trigger_error('Variable $discordId is empty', E_USER_ERROR);
    }
    if (!self::exists($discordId)) {
      if ($discordId != -1) {
        trigger_error('Object User with a discordId of ' . $discordId . ' does not exist', E_USER_ERROR);
      }
      return "user doesnt exist";
    }
    
    $this->discordId = $discordId;
    
    if (FishyUtils::getInstance()->hasErrors()) {
      return false;
    }
    $statement = dbConnection::getInstance()->prepare('SELECT description, roles, username, discriminator, avatar, bsaber '
            . 'FROM users '
            . 'WHERE discordid = CAST(:id AS bigint)');
    $statement->bindParam(':id', $this->discordId);
    
    $statement->execute();
    
      while ($row = $statement->fetch()) {
        $this->description = $row[0];
        $tmpRoles = explode(',', str_replace("\"","", substr($row[1], 1, -1)));
        foreach ($tmpRoles as $role) {
          $tempRole = new Role();
          $tempRole->setId($role);
          $tempRole->read();
          $roles[$role] = $tempRole;
          unset($tempRole);
        }
        uasort($roles, array($this, "sortRoles"));
        $this->roles = $roles;
        $this->username = $row[2];
        $this->discriminator = $row[3];
        $this->avatar = $row[4];
        $this->bsaber = $row[5];
        $this->isLoggedIn = true;
        $output = true;
      }
    
    FishyUtils::getInstance()->log("Finished reading user with parameters: $this->discordId");
    return $output;
  }
  
  public function readAll() {
    $output = false;
    
    $statement = dbConnection::getInstance()->prepare('SELECT discordid FROM users');
    $statement->execute();
    
    while ($row = $statement->fetch()) {
      $user = new User();
      $user->read($row[0]);
      $output[] = $user;
      unset($user);
    }
    return $output;
  }
  
  public function readAdmins() {
    $output = false;
    
    $statement = dbConnection::getInstance()->prepare("SELECT discordid FROM users WHERE roles @> ARRAY['admin']");
    $statement->execute();
    
    while ($row = $statement->fetch()) {
      $output[] = $row[0];
    }
    return $output;
  }
  
  public function update($values) {
    global $currentUser;
    
    FishyUtils::getInstance()->log("Updating user");
    
    $admin = false;
    if (isset($values['admin']) && $currentUser->isVerified() && $currentUser->isAdmin()) {
      $admin = $values['admin'];
    }
    
    $description = strip_tags($values['description']);
    if ($admin) {
      if ($currentUser->canUpdateUserRoles()) {
        $roles = '{' . $values['roles'] . '}';
      }
    } else {
      $avatar = session('discord_user')->avatar;
    }
    
    if (empty($this->discordId)) {
      trigger_error('Variable $this->discordId is empty, i have no clue how you did that', E_USER_ERROR);
    }
    
    if (FishyUtils::getInstance()->hasErrors()) {
      return false;
    }
    if ($admin) {
      $statement = dbConnection::getInstance()->prepare('UPDATE users '
              . 'SET description = :description, bsaber = :bsaber, '
              . 'username = :username, discriminator = :discriminator, '
              . 'roles = :roles '
              . 'WHERE discordid = :discordid '
              . 'RETURNING discordid');
      $statement->bindParam(':username', $values['username']);
      $statement->bindParam(':discriminator', $values['discriminator']);
      $statement->bindParam(':roles', $roles);
    } else {
      $statement = dbConnection::getInstance()->prepare('UPDATE users '
              . 'SET description = :description, bsaber = :bsaber, '
              . 'avatar = :avatar '
              . 'WHERE discordid = :discordid '
              . 'RETURNING discordid');
      $statement->bindParam(':avatar', $avatar);
    }
    $statement->bindParam(':description', $description);
    $statement->bindParam(':bsaber', $values['bsaber']);
    $statement->bindParam(':discordid', $this->discordId);
    
    $output = $statement->execute();
    
    FishyUtils::getInstance()->log("Finished updating user with id: $this->discordId. Output: " . $output);
    return $output;
  }
  
  protected function delete() {
    //todo
  }

  public static function exists($discordId) {    
//    FishyUtils::getInstance()->log("Checking if user with discordId of $discordId exists");
    
    if (empty($discordId)) {
      trigger_error('Variable $discordId is empty', E_USER_ERROR);
    }
    
    if (FishyUtils::getInstance()->hasErrors()) {
      return false;
    }
    $statement = dbConnection::getInstance()->prepare('SELECT EXISTS '
            . '(SELECT 1 FROM users WHERE discordid = CAST(:discordid AS bigint))');
    $statement->bindParam(':discordid', $discordId);

    $statement->execute();
    while ($row = $statement->fetch()) {
      $output = $row[0];
    }
    
//    FishyUtils::getInstance()->log("Finished checking if user with discordId of $discordId exists. Return: " . $output);
    return $output;
  }
  
  public function acceptTOS() {
    FishyUtils::getInstance()->log("Accepting TOS for user with discordId of $this->discordId");
    
    if (empty($this->discordId)) {
      trigger_error('Variable $this->discordId is empty, i have no clue how you did this', E_USER_ERROR);
    }
    
    if (FishyUtils::getInstance()->hasErrors()) {
      return false;
    }
    $statement = dbConnection::getInstance()->prepare('UPDATE users '
            . "SET roles = array_append(roles, 'verified') "
            . "WHERE discordid = CAST(:discordid AS bigint) "
            . "RETURNING discordid");
    $statement->bindParam(':discordid', $this->discordId);
    
    $output = $statement->execute();
    return $output;
  }
  
  public function makeComment($modelId, $message) {
    if (!$this->canComment()) {
      trigger_error('User ' . $this->discordId . ' is not allowed to post comments', E_USER_WARNING);
    }
    
    FishyUtils::getInstance()->log("Creating a comment with the message '$message' for model with the id of $modelId");
    
    $message = strip_tags($message, SUPPORTEDTAGS);
    $id = time();
    if (empty($message)) {
      trigger_error('Variable $message is empty', E_USER_ERROR);
    }
    
    if (FishyUtils::getInstance()->hasErrors()) {
      return false;
    }
    $statement = dbConnection::getInstance()->prepare('INSERT INTO comments (commentid, discordid, message) '
            . 'VALUES (:commentid, :discordid, :message)');
    $statement->bindParam(':commentid', $id);
    $statement->bindParam(':discordid', $this->discordId);
    $statement->bindParam(':message', $message);
    
    $statement->execute();
    
    $statement = dbConnection::getInstance()->prepare('UPDATE models '
            . 'SET commentids = array_append(commentids, :commentid) '
            . 'WHERE id = CAST(:modelid AS bigint)');
    $statement->bindParam(':commentid', $id);
    $statement->bindParam(':modelid', $modelId);
    
    $statement->execute();
    
    return true;
  }
  
  public function getComment($commentId, $modelAuthorId) {
//    FishyUtils::getInstance()->log("Reading comment with the id of $commentId for model with the id of $modelAuthorId");
    
    if (FishyUtils::getInstance()->hasErrors()) {
      return false;
    }
    $statement = dbConnection::getInstance()->prepare('SELECT discordid, message '
            . 'FROM comments '
            . 'WHERE commentid = CAST(:commentid AS bigint)');
    $statement->bindParam(':commentid', $commentId);
    
    $statement->execute();
    
    while ($row = $statement->fetch()) {
      $posterDiscordId = $row[0];
      $message = $row[1];
      $this->read($posterDiscordId);
      $commentor = $this;
    }
    
    
    include ROOT . "/resources/components/comment.php";
    return true;
  }
  
  public function deleteComment($commentId, $posterId = null) {    
    FishyUtils::getInstance()->log("Deleting comment with the id of $commentId");
    
    if (!$this->isAdmin() && $this->discordId !== $posterId) {
      trigger_error("The current user is not an admin or the poster of the comment with the id of $commentId", E_USER_ERROR);
    }
    $poster = "*";
    if ($this->discordId == $posterId) {
      $poster = $posterId;
    }
    
    if (FishyUtils::getInstance()->hasErrors()) {
      return false;
    }
    $statement = dbConnection::getInstance()->prepare('DELETE FROM comments '
            . 'WHERE commentid = CAST(:commentid AS bigint) AND discordid LIKE :discordid');
    $statement->bindParam(':commentid', $commentId);
    $statement->bindParam(':discordid', $poster);
    
    $statement->execute();
    
    $statement = dbConnection::getInstance()->prepare('UPDATE models '
            . 'SET commentids = array_remove(commentids, :commentid)');
    $statement->bindParam(':commentid', $commentId);
    
    $statement->execute();
      
    return true;
  }
  
  public function checkAvatar() {
    $output = false;
    
    if ($this->avatar !== session('discord_user')->avatar) {
      $avatar = session('discord_user')->avatar;
      
      $statement = dbConnection::getInstance()->prepare('UPDATE users '
              . 'SET avatar = :avatar '
              . 'WHERE discordid = CAST(:discordid AS bigint)');
      $statement->bindParam(':avatar', $avatar);
      $statement->bindParam(':discordid', $this->discordId);
      
      $output = $statement->execute();
    } else {
      $output = true;
    }
    
    return $output;
  }
  
  public function getVote($modelId) {
    $output = null;
    
    $statement = dbConnection::getInstance()->prepare('SELECT isupvote '
            . 'FROM votes '
            . 'WHERE modelid = :modelid AND userid = :userid');
    $statement->bindParam(':modelid', $modelId);
    $statement->bindParam(':userid', $this->discordId);
      
    $statement->execute();
    
    while ($row = $statement->fetch()) {
      switch ($row[0]) {
        case 't':
          $output = true;
          break;
        case 'f':
          $output = false;
          break;
        default:
          $output = null;
          break;
      }
    }
    
    return $output;
  }
  
  public function vote($modelId, $isUpVote = true) {
    $output = false;
    
    $currentVote = $this->getVote($modelId);
    
    if ($isUpVote !== NULL) {
      switch ($isUpVote) {
        case true:
          $vote = 't';
          break;
        case false:
          $vote = 'f';
          break;
        default:
          return false;
          break;
      }
    }
    
    if ($currentVote === NULL) {
      //if the user hasn't voted for the specified model yet insert a new row
      $statement = dbConnection::getInstance()->prepare('INSERT INTO votes (modelid, userid, isupvote) '
              . 'VALUES (CAST(:modelid AS bigint), :discordid, :vote)');
      $statement->bindParam(':modelid', $modelId);
      $statement->bindParam(':discordid', $this->discordId);
      $statement->bindParam(':vote', $vote);
    } else if ($currentVote === $isUpVote || $isUpVote === NULL) {
      //if the user removes their vote delete their row
      $statement = dbConnection::getInstance()->prepare('DELETE FROM votes '
              . 'WHERE modelid = CAST(:modelid AS bigint) AND userid = :userid');
      $statement->bindParam(':modelid', $modelId);
      $statement->bindParam(':userid', $this->discordId);
      
    } else if ($currentVote !== $isUpVote) {
      //if the user changes their vote update it
      $statement = dbConnection::getInstance()->prepare('UPDATE votes '
              . 'SET isupvote = :vote '
              . 'WHERE modelid = CAST(:modelid AS bigint) AND userid = :userid');
      $statement->bindParam(':modelid', $modelId);
      $statement->bindParam(':userid', $this->discordId);
    }
    
    $output = $statement->execute();
    
    return $output;
  }

  protected function sortRoles($a, $b) {
    return ($a->getPriority() < $b->getPriority());
  }
  
  protected function findRoleImage() {
    $output = '';
    
    $priority = 0;
    foreach ($this->roles as $key => $role) {
      if ($role->getPriority() > $priority && $role->getImage() !== false) {
        $priority = $role->getPriority();
        $output = $key;
      }
    }
    return (!empty($output))? $output : false;
  }
  
  public function getChatRoles() {
    $output = false;
    
    foreach ($this->roles as $role) {
      if (!empty($role->getChatTitle())) {
        $temp['title'] = $role->getChatTitle();
        if (strpos($role->getColor(), '.') === 0) {
          $temp['class'] = substr($role->getColor(), 1);
        } else {
          $temp['class'] = '';
        }
        
        if (strpos($role->getColor(), '#') === 0) {
          $temp['hex'] = substr($role->getColor(), 1);
        } else {
          $temp['hex'] = '';
        }
        
        $output[] = $temp;
      }
    }
    return $output;
  }
  
  //Permission Functions
  public function canComment() {
    return $this->hasPermission('can_comment');
  }
  public function canUpload() {
    return $this->hasPermission('can_upload');
  }
  public function canApprove() {
    return $this->hasPermission('can_approve');
  }
  public function canUpdateUserRoles() {
    return $this->hasPermission('can_update_user_roles');
  }
  protected function hasPermission($permission) {
    $output = null;
    if (isset($this->roles)) {
      foreach ($this->roles as $role) {
        if ($role->hasPermission($permission) && $output === null) {
          $output = ($role->hasPermission($permission));
        }
      }
    }
    if ($output === null) {
      $output = false;
    }
    return $output;
  }
  
  //Role Functions
  public function isAdmin() {
    return $this->hasRole('admin');
  }
  public function isBSMGStaff() {
    return $this->hasRole('bsmg');
  }
  public function isBobby() {
    return $this->hasRole('bobby');
  }
  public function isGay() {
    return $this->hasRole('gay');
  }
  public function isJoelseph() {
    return $this->hasRole('joelseph');
  }
  public function isTrusted() {
    return $this->hasRole('trusted');
  }
  public function isModelsaberDeveloper() {
    return $this->hasRole('modelsaber developer');
  }
  public function isGameDeveloper() {
    return $this->hasRole('game developer');
  }
  public function hasRole($role) {
    if (isset($this->roles)) {
//      return (array_search($role, $this->roles) !== FALSE)? TRUE : FALSE;
//      return (count(array_keys($this->roles, $role)) > 0);
      return (isset($this->roles[$role]));
    } else {
      return false;
    }
  }
}
