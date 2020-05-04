<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * A class designed to take care of webhooks.
 *
 * @author Lauge Rud Knudsen <laugerudknudsen@gmail.com>
 * @version V2
 */
class Webhook {
  //properties
  private $type;
  private $name;
  private $author;
  private $image;
  private $id;
  private $tags;
  private $link;
  private $description;
  private $settings;
  private $video = ' ';
  private $dependencies = '';
  private $platform = 'pc';
  private $superType;
  private $curl;
  private $payload;
  private $shouldPost = true;
  
  //constants
  const MESSAGE_SUCCESS = 'The webhook was successfully sent';
  const MESSAGE_FAILURE = 'The webhook failed to send';
  const MESSAGE_ERROR = 'An error has occured when trying to recieve the message';
  const MESSAGE_UNSUPPORTED = 'Webhooks are not supported in this environment';
  
  //getters
  /**
   * Get the message for posting.
   * 
   * @param bool $value a boolean from the results of post.
   * @return string the appropriate message.
   */
  public static function getMessage($value) {
    return ($value) ? self::MESSAGE_SUCCESS : self::MESSAGE_FAILURE;
  }
  
  //setters
  
  //public methods
  function __construct($id) {
    $model = new Model();
    $model->readSingle($id);
    
    $this->id = $id;

    foreach ($model->getTags() as $tempTag) {
      if (strtolower($tempTag) === 'nsfw') {
        $this->shouldPost = false;
      }
    }
    
    $tags = join(', ' . $model->getTags());
    $this->tags = str_replace("@", '@' . json_decode('"\u200b"'), $tags);
    
    $this->description = $this->sanitizeWebhook($model->getDescription());
    $this->name = $this->sanitizeWebhook($model->getName());
    $this->link = $this->sanitizeWebhook($model->getLink());
    
    $this->type = $model->getType();
    $this->superType = getSuperType($this->type);
    
    if (!empty($model->getDiscordId()) && $model->getDiscordId() !== -1) {
      $author = new User();
      $author->read($model->getDiscordId());
    } else {
      $author = $model->getAuthor();
    }
    $this->author = $author;
    
    $this->image = $model->getImage();
    
    //settings
    $settingsPath = WEBROOT . '/files/' . $this->type . '/' . $this->id . '/settings.json';
    if (file_exists($settingsPath)) {
      $this->settings = json_decode(file_get_contents($settingsPath), true);
    }
    
    //description
    if (isset($this->settings) && $this->settings['embed']['description']) {
      $this->video .= $this->settings['embed']['description'];
    }
    
    //video
    if (isset($this->settings) && $this->settings['embed']['useVideo']) {
      if (!empty($this->video)) {
        $this->video .= '\n\n';
      }
      $this->video .= $this->settings['model']['video'];
    }
    
    //dependencies
    if (isset($this->settings) && !empty($this->settings['model']['dependencies'])) {
      $dependencies = '';
      $depTypes = ['mods', 'scripts'];
      $firstRun = true;

      foreach ($depTypes as $depType) {
        if (!empty($this->settings['model']['dependencies'][$depType])) {
          if ($firstRun) {
            $firstRun = false;
          } else {
            $dependencies .= '\n\n';
          }
          $dependencies .= '__**' . ucfirst($depType) . '**__';

          foreach ($this->settings['model']['dependencies'][$depType] as $depName => $depVersion) {
            if (!empty($depName) && !empty($depVersion)) {
              if (!empty($depName)) {
                $dependencies .= '\n';
              }
              $dependencies .= '**' . $this->sanitizeWebhook($depName) . '**: ' . $this->sanitizeWebhook($depVersion);
            }
          }
        }
      }
      
      $this->dependencies = $dependencies;
    }
    
    //fields
    //tags
    if (!empty($this->tags)) {
      $fields[] = [
          "name" => "Tags:",
          "value" => "$this->tags"
      ];
    }

    //features
    if (isset($this->settings) && !empty($this->settings['features'])) {
      $maxlen = max(array_map('strlen', array_keys($this->settings['features'])));
      foreach ($this->settings['features'] as $key => $checkMark) {
        $padding = str_repeat(' ', $maxlen - strlen($key));
        $features .= $key . ': ' . $padding . ($checkMark)? ':white_check_mark:': ':x:' . '\n';
      }
      $features = substr($features, 0, -2);

      $fields[] = [
          "name" => "Features:",
          "value" => "$features"
      ];
    }

    //dependencies
    if (!empty($this->dependencies)) {
      $fields[] = [
          "name" => "Dependencies:",
          "value" => "$this->dependencies"
      ];
    }
    
    if (empty($fields)) {
      $fields = [];
    }

    if (gettype($this->author) == 'object') {
      $discordUsername =  $this->author->getUsername();
    } else {
      $discordUsername =  $this->author;
    }
    if (gettype($this->author) == 'object') {
      $discordId =  $this->author->getDiscordId();
    } else {
      $discordId =  '';
    }
    if (gettype($this->author) == 'object') {
      $discordAvatar =  $this->author->getAvatar();
    } else {
      $discordAvatar =  'https://cdn.discordapp.com/embed/avatars/1.png';
    }
    
    $this->payload = json_encode(
          [
      "content" => "$this->video",
      "embeds" => [
          [
              "title" => "$this->name",
              "description" => "$this->description",
              "url" => WEBROOT . "/$this->superType/?id=$this->id&$this->platform",
              "color" => hexdec('#535aa2'),
              "timestamp" => date('Y-m-d h:i:s', $id),
              "footer" => [
                  "icon_url" => WEBROOT . '/resources/manifest/icon-192.png',
                  "text" => SITECAMEL
              ],
              "thumbnail" => [
                  "url" => WEBROOT . '/resources/' . $this->type . '-192.png'
              ],
              "image" => [
                  "url" => "$this->image"
              ],
              "author" => [
                  "name" => $this->sanitizeWebhook($discordUsername),
                  "url" => WEBROOT . "/Profile/?user=" . $discordId,
                  "icon_url" => $discordAvatar . "?size=64"
              ],
              "fields" => $fields,
          ],
          [
              "title" => "**Download**",
              "url" => "$this->link",
              "color" => hexdec('#48f442')
          ]
      ]
          ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
  }
  
  /**
   * Post the webhook.
   */
  public function post() {
    if (!$this->shouldPost) {
      return;
    }
    
    $password = $this->getPassword();
    
    if (!empty($password) && $password !== FALSE) {
      $this->curl = curl_init($password);
      $log = fopen(ROOT . '/webhook_queries', 'a');
      fwrite($log, $this->payload);
      fclose($log);

      $header = [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($this->payload)
      ];

      curl_setopt($this->curl, CURLOPT_POST, 1);
      curl_setopt($this->curl, CURLOPT_HTTPHEADER, $header);
      curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->payload);
      $result = curl_exec($this->curl);

      $log = fopen(ROOT . '/webhook_results', 'a');
      fwrite($log, $result);
      fclose($log);
      
      $this->setMessage(!empty($result));
    }
  }
  
  //private methods
  /**
   * Sanitizes a string.
   * 
   * @param string $string the string to be sanitized.
   * @return string the sanitized output.
   */
  private function sanitizeWebhook($string) {
    if (strpos($string, '@everyone') !== false) {
      $string = substr(str_replace("@", '@' . json_decode('"\u200b"'), $string), 1, -1);
    }
    return $string;
  }
  
  /**
   * Gets the appropriate password for this webhook.
   * 
   * @return string the appropriate password.
   */
  private function getPassword() {
    global $helper;
    
    $passwordKey = 'WEBHOOK_' . strtoupper($this->platform) . '_';
    switch ($this->platform) {
      case 'pc':
        switch ($this->type) {
          case 'avatar':
            $passwordKey .= 'AVATARS';
            break;
          case 'saber':
            $passwordKey .= 'SABERS';
            break;
          case 'platform':
            $passwordKey .= 'PLATFORMS';
            break;
          case 'bloq':
            $passwordKey .= 'NOTES';
            break;
        }
        break;
      case 'quest':
        switch ($this->type) {
          case 'avatar':
            $passwordKey .= 'AVATARS';
            break;
          case 'saber':
            $passwordKey .= 'SABERS';
            break;
          case 'platform':
            $passwordKey .= 'PLATFORMS';
            break;
          case 'bloq':
            $passwordKey .= 'NOTES';
            break;
          // disabled as they aren't used
          // case 'trail':
          //   $passwordKey .= 'TRAIL';
          //   break;
          // case 'sign':
          //   $passwordKey .= 'SIGN';
          //   break;
          case 'misc':
            $passwordKey .= 'MISC';
            break;
        }
        break;
    }
    
    return $helper->setting($passwordKey);
  }
  
  /**
   * Saves a message with the status of the webhook to the session.
   * 
   * @param int $messageCode the message code.
   */
  private function setMessage($messageCode) {
    $output = self::MESSAGE_ERROR;
    switch ($messageCode) {
      case 0:
        $output = self::MESSAGE_FAILURE;
        break;
      case 1:
        $output = self::MESSAGE_SUCCESS;
        break;
      case 2:
        $output = self::MESSAGE_UNSUPPORTED;
        break;
    }
    $_SESSION['webhookMessage'] = $output;
  }
}
