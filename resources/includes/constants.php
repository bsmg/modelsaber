<?php

//Require the helper, without this more or less nothing will work
require_once __DIR__ . '../../../helper.php';
$helper = Helper::getInstance();

/** The current site environment. */
define('ENV', $helper->env());

/** The absolute path to the root of the site. */
define('ROOT', $helper->setting('ROOT'));
/** The relative path to the root of the site. */
define('WEBROOT', $helper->setting('WEBROOT'));
/** The path to the php cli executable. */
define('PHP_CLI', $helper->setting('PHP_CLI'));

//Discord OAuth
define('OAUTH2_CLIENT_ID', $helper->setting('OAUTH2_CLIENT_ID'));
define('OAUTH2_CLIENT_SECRET', $helper->setting('OAUTH2_CLIENT_SECRET'));
define('OAUTH2_BOT_TOKEN', $helper->setting('OAUTH2_BOT_TOKEN'));

//Database
/** The username for the database. */
define('DATABASE_USER', $helper->setting('DATABASE_USER'));
/** The password for the database. */
define('DATABASE_PASSWORD', $helper->setting('DATABASE_PASSWORD'));
/** The name of the database. */
define('DATABASE_NAME', $helper->setting('DATABASE_NAME'));

//Site
/** The display name of the website. */
define('SITE', $helper->setting('SITE'));
/** The display name of the website in CamelCase. */
define('SITECAMEL', $helper->setting('SITECAMEL'));
/** The text to be shown under the copyright. */
define('FOOTBLOQ', $helper->setting('FOOTBLOQ'));
/**
 * The info about the current mod installer.
 * 
 * Keys:
 * - name: The name to be shown.
 * - link: The link to the mod installer.
 */
define('MODINSTALLER', ['name' => $helper->setting('MODINSTALLER_NAME'), 'link' => $helper->setting('MODINSTALLER_LINK')]);

//Fishy Utils
/** The error logging mode. */
define('FISHY_ERROR_MODE', $helper->setting('FISHY_ERROR_MODE'));
/** The path to the error log. */
define('FISHY_LOG_PATH', $helper->setting('FISHY_LOG_PATH'));

//Model Types
define('AVATAR', 'avatar');
define('AVATARS', 'avatars');
define('SABER', 'saber');
define('SABERS', 'sabers');
define('PLATFORM', 'platform');
define('PLATFORMS', 'platforms');
define('NOTE', 'bloq');
define('NOTES', 'bloqs');
define('TRAIL', 'trail');
define('TRAILS', 'trails');
define('SIGN', 'sign');
define('SIGNS', 'signs');
define('MISC', 'misc');
define('MISCS', MISC);
define('TYPE', [AVATAR, SABER, PLATFORM, NOTE]);
define('QUEST_TYPE', [SABER, TRAIL, PLATFORM, NOTE, SIGN, MISC]);

//User Agent
define('USERAGENT', $_SERVER['HTTP_USER_AGENT']? : "");

$DEVICEQUEST = false;
$DEVICEPC = false;
if (strpos(strtolower(USERAGENT), 'beaton_quest') !== false || strpos(strtolower(USERAGENT), 'sidequest') !== false) {
  $DEVICEQUEST = true;
}
if ($DEVICEQUEST == false) {
  $DEVICEPC = true;
}
define('DEVICE', [
    'quest' => $DEVICEQUEST,
    'pc' => $DEVICEPC
    ]);

//Platform Specific
define('MOBILETABS', [ucfirst(SABERS), ucfirst(PLATFORMS), ucfirst(NOTES), ucfirst(MISC)]);
define('PCTABS', [ucfirst(AVATARS), ucfirst(SABERS), ucfirst(PLATFORMS), ucfirst(NOTES)]);

//Tags
define('SPECIALTAGS', [
    'custom colors' => 'has-border-gay',
    'anime' => 'has-border-weeb-trash',
    'custom trail' => 'has-border-trail']);

//Upload Specific
define('FFMPEG_PATH', 'ffmpeg');
define('SUPPORTEDTAGS', '<b><br><hr><strong><em>');
define('SUPPORTEDTAGSFORMATED', "<b>, <br>, <hr>, <strong>, and <em>");
define('MINIMAGESIZE', $helper->setting('MIN_IMAGE_SIZE'));
define('MAXVIDEOFILESIZE', $helper->setting('MAX_VIDEO_FILESIZE')); //in MB
define('MARKDOWNSUPPORT', 'Supports markdown');
define('SUPPORTEDIMAGES', ['png', 'apng', 'jpg', 'jpeg', 'gif', 'svg', 'mp4', 'webm']);
define('SUPPORTED_IMAGE_EXTENSIONS', '.png,.jpg,.gif,.svg,.mp4,.webm');
//Optimization
//Images
/**
 * PNG Optimization Level.
 * If set to 0, it will use minimal effort.
 * If set to 1, it will take what it *thinks* is most efficient.
 * If set to 2 or higher, the value becomes the amount of data streams.
 */
define('PNGOPTIMIZATION', 3);

/**
 * JPEG Quality.
 * Determines how much quality should be preserved.
 * A setting of 100 has no visible quality loss, but still compresses the image.
 * *note: A setting of 100 is NOT the same as lossless*
 */
define('JPEGQUALITY', 100);

//Error Handling
if (ENV == 'local') {
  $errorMode = 'console';
//  $errorMode = 'print';
} else if (ENV == 'production') {
  $errorMode = 'hidden';
} else {
  $errorMode = 'print';
}

//About Page
//A max of 4 contributions please, otherwise you have to scroll down to see the rest of a card.
//Assistant
$aboutContributers['assistant'] = [
    'name' => 'Assistant',
    'title' => 'The original Support',
    'description' => 'The original creator of Modelsaber.',
    'discord' => 'Assistant#8431',
    'image' => 'assistant.png',
    'contributions' => ['The first iteration of the website']
];
//Steven
$aboutContributers['steven'] = [
    'name' => 'Steven',
    'title' => 'THE ModelSaber Admin',
    'description' => 'Klouder is cute',
    'discord' => 'Steven🎀#0001',
    'image' => 'steven.png',
    'contributions' => ['Handles all model approvals', 'Dark Theme', 'Maintains Custom Avatars']
];
//Laugexd
$aboutContributers['laugexd'] = [
    'name' => 'laugexd',
    'title' => 'Fishy Boi',
    'description' => 'Continued after assistant because modelsaber was open source.',
    'discord' => 'laugexd#1395',
    'image' => 'laugexd.png',
    'contributions' => ['Discord login', 'Bloq support', 'Variations support', 'Multiple file upload support']
];
//Bobbie
$aboutContributers['bobbie'] = [
    'name' => 'bobbie',
    'title' => 'Bobby',
    'description' => 'High glucose alert!',
    'discord' => 'bobbie#0001',
    'image' => 'bobbie.png',
    'contributions' => ['Model approvals', 'A bunch of bloqs']
];

//Copyright
$copyrights['Markdown'] = [
    'license' => 'MIT',
    'title' => 'Parsedown',
    'link' => 'https://parsedown.org/',
    'year' => '2013-2018',
    'owner' => [
        'name' => 'Emanuil Rusev',
        'website' => 'https://erusev.com/'
    ]
];

//Approval Statuses
/**
 * Verified
 * 
 * not used but i thought i'd include it if you wanted to have some kind of verified system.
 */
define('VERIFIED', 'verified');
/**
 * Approved
 * 
 * The value of a model that has been approved
 */
define('APPROVED', 'approved');
/**
 * Unapproved
 * 
 * The value of models that are still waiting to be reviewed
 */
define('UNAPPROVED', 'unapproved');
/**
 * Declined
 * 
 * The value of models that have not met the requirements to be approved
 */
define('DECLINED', 'declined');
/**
 * Status List
 * 
 * An indexed array of the different status constants for use in inputs
 */
define('STATUSLIST', [ucfirst(VERIFIED), ucfirst(APPROVED), ucfirst(UNAPPROVED), ucfirst(DECLINED)]);

//clipboard message
define('CLIPBOARD', 'copy to clipboard');

//Start session
//if (version_compare(PHP_VERSION, '5.4.0', '<')) {
//    if (session_id() == '') {
//      session_start();
//    }
//} else {
//   if (session_status() == PHP_SESSION_NONE) {
//     session_start();
//   }
//} 
require_once ROOT . '/sessionStart.php';

//Require Fishy Utils
require_once __DIR__ . '/fishyUtils.php';
//$fishyUtils = FishyUtils::getInstance();

/**
 * Get the super types.
 * 
 * @return string[] An array containing the TYPE constant as a super type.
 */
function getTypes() {
  foreach (TYPE as $type) {
    $output[] = strtolower(getSuperType($type));
  }
  // array_push($output, getQuestTypes());
  $output = array_merge($output, getQuestTypes());
  // $output = array_unique($output);
  
  return $output;
}

/**
 * Get the super types for quest.
 * 
 * @return string[] An array containing the QUEST_TYPE constant as a super type.
 */
function getQuestTypes() {
  foreach (QUEST_TYPE as $type) {
    $output[] = strtolower(getSuperType($type));
  }
  
  return $output;
}
/**
 * Get Url Type
 * 
 * Converts the current url to a modeltype.
 * 
 * @return string|null $type Returns null if the model type wasn't found
 */
function getUrlType() {
  $output = null;
  $types = getTypes();
  $url = strtolower($_SERVER['PHP_SELF']);
  foreach ($types as $type) {
    if (strpos($url, '/' . $type) !== FALSE) {
      $output = $type;
    }
  }
  
  return (isType($output)) ? $output : null;
}

/**
 * Executes a command as a background task.
 * 
 * @param string $cmd The command to be executed.
 */
function execInBackground($cmd){
    if (substr(php_uname(), 0, 7) == "Windows"){ 
        pclose(popen("start /B ". $cmd, "r"));  
    }else{ 
        exec($cmd . " > /dev/null &");   
    } 
} 

/**
 * Type To Path
 * 
 * Converts a model type constant to it's filesystem path variant.
 * 
 * @param string $type
 * @return string|null Returns null if the modeltype wasn't found.
 */
function typeToPath($type) {
  switch (strtolower($type)) {
    case AVATAR:
    case AVATARS:
      return "avatar";
    case SABER:
    case SABERS:
      return "saber";
    case PLATFORM:
    case PLATFORMS:
      return "platform";
    case NOTE:
    case NOTES:
      return "bloq";
    case TRAIL:
    case TRAILS:
      return "trail";
    case SIGN:
    case SIGNS:
      return "sign";
    case MISC:
    case MISCS:
      return "misc";
    default:
      return null;
  }
}

/**
 * Type To Extension
 * 
 * Converts a model type constant to it's file extension variant.
 * 
 * @param string $type
 * @return string|null Returns null if the modeltype wasn't found.
 */
function typeToExtension($type) {
  switch (strtolower($type)) {
    case AVATAR:
    case AVATARS:
      return "avatar";
    case SABER:
    case SABERS:
      return "saber";
    case PLATFORM:
    case PLATFORMS:
      return "plat";
    case NOTE:
    case NOTES:
      return "bloq";
    case TRAIL:
    case TRAILS:
      return "trail";
    case SIGN:
    case SIGNS:
      return "sign";
    case MISC:
    case MISCS:
      return "misc";
    default:
      return null;
  }
}

/**
 * Get the supertype of the requested model type.
 * 
 * @param string $type the requested model type.
 * @return string the $type converted to a supertype or null if no type was found.
 */
function getSuperType($type) {
  switch (strtolower($type)) {
    case AVATAR:
    case AVATARS:
      return ucfirst(AVATARS);
    case SABER:
    case SABERS:
      return ucfirst(SABERS);
    case PLATFORM:
    case PLATFORMS:
      return ucfirst(PLATFORMS);
    case NOTE:
    case NOTES:
      return ucfirst(NOTES);
    case TRAIL:
    case TRAILS:
      return ucfirst(TRAILS);
    case SIGN:
    case SIGNS:
      return ucfirst(SIGNS);
    case MISC:
    case MISCS:
      return ucfirst(MISCS);
    default:
      return null;
  }
}

/**
 * Is Type
 * 
 * Checks if the given string is a type.
 * 
 * @param string $type
 * @return boolean
 */
function isType($type) {
  switch (strtolower($type)) {
    case AVATAR:
    case AVATARS:
    case SABER:
    case SABERS:
    case PLATFORM:
    case PLATFORMS:
    case NOTE:
    case NOTES:
    case TRAIL:
    case TRAILS:
    case SIGN:
    case SIGNS:
    case MISC:
    case MISCS:
      return true;
    default:
      return false;
  }
}

function preg_grep_keys($pattern, $input, $flags = 0) {
    return array_intersect_key($input, array_flip(preg_grep($pattern, array_keys($input), $flags)));
}

/**
 * Failed
 * 
 * Handles errors when creating a new model.
 * 
 * @param string $url The redirect url.
 * @param string $message The message shown on the page
 */
function failed($url, $message) {
  include ROOT . '/resources/templates/uploadFailed.php';
}

/**
 * HTTPError
 * 
 * Redirects the user to the specified error page.
 * 
 * @param int $error The http error page used for redirecting.
 */
function HTTPError($error, $useMeta = false) {
  if ($useMeta) {
    switch ($error) {
      case 403:
        echo '<meta http-equiv="refresh" content="0;url=' . WEBROOT . '/Error/403.php">';
        break;
    }
  }
  switch($error) {
    case 403:
      header('Location: ' . WEBROOT . '/Error/403.php');
      break;
  }
}

/**
 * Checks if the specified path is in the url.
 * 
 * @param string $search The path to search for.
 * @return boolean
 */
function findUrl($search) {
  $output = false;
  if (strpos(strtolower($_SERVER['PHP_SELF']), '/' . strtolower($search) . '/') !== false) {
    $output = true;
  }
  return $output;
}

/**
 * Used to make sure each info box has a unique id.
 */
$infoIndex = 0;
/**
 * Prints an information button to the page.
 * 
 * @param string $message The message that should be deisplayed on hover.
 * @param string $size An optional size parameter, defailt is 'small'.
 */
function info($message, $size = 'small', $isFile = false) {
  global $infoIndex;
  
  switch ($size) {
    case 'large':
      $fontsize = '2em';
      break;
    case 'medium':
      $fontsize = '1.5em';
      break;
    case 'small':
      $fontsize = '1em';
      break;
  }
  
  include ROOT . '/resources/components/infoButton.php';
  $infoIndex++;
}

/**
 * Searches for the specified cookie.
 * 
 * @param string $cookieString.
 * @return string Returns 'checked' if found, '' otherwise.
 */
function adminCookie($cookieString) {
  $output = "";
  
  if (!isset($_COOKIE[$cookieString])) {
    $output = "checked";
  } else if ($_COOKIE[$cookieString] == "checked") {
    $output = "checked";
  } else {
    $output = "";
  }
  return $output;
}

//Current device platform
/**
 * Get the current device platform.
 * 
 * This is used for showing the correct page for the correct platform and setting
 * the links on pages to use the correct GET parameter.
 * 
 * @return string Holds the value specifying the viewing platform.
 */
function getDevicePlatform() {
  if (isset($_GET['quest']) || DEVICE['quest']) {
    $device = 'quest';
  } else if (isset($_GET['pc'])) {
    $device = 'pc';
  } else {
    $device = 'pc';
  }
  
  return $device;
}

/**
 * Used to get the correct suffix for URLs.
 * 
 * @param string $type The model type.
 * @return string The device suffix.
 */
function getDeviceTab($type) {
  $output = "";
  //$_SERVER['PHP_SELF'] == WEBROOT . '/' . ucfirst($type) . '/index.php'
  if (strpos($_SERVER['PHP_SELF'], '/' . ucfirst($type)) !== FALSE) {
    $classes[] = 'is-active';
  }
  switch (getDevicePlatform()) {
    case 'pc':
      if (array_search(ucfirst($type), PCTABS) === FALSE) {
        $classes[] = 'is-hidden';
      }
      break;
    case 'quest':
      if (array_search(ucfirst($type), MOBILETABS) === FALSE) {
        $classes[] = 'is-hidden';
      }
      break;
  }
  if (isset($classes)) {
    $output = 'class="';
    foreach ($classes as $class) {
      $output .= $class . ' ';
    }
    $output .= '"';
  }
  return $output;
}

//Initialization
if (FISHY_ERROR_MODE !== 'off') {
  set_error_handler([FishyUtils::getInstance(), 'errorHandler']);
}

//Database Setup
require_once ROOT . '/dbConnection.php';
dbConnection::getInstance()->start();

//markdown support
include_once ROOT . '/parsedown-1.7.3/Parsedown.php';
$parsedown = new Parsedown();
$parsedown->setSafeMode(true);

require_once ROOT . '/discordOAuth.php';
include_once ROOT . '/image.php';
include_once ROOT . '/file.php';
require_once ROOT . '/user.php';
require_once ROOT . '/notification.php';
require_once ROOT . '/role.php';
require_once ROOT . '/permission.php';

//setup the current user
$currentUser = new User();
if (!empty(session('discord_user'))) {
  $userExists = $currentUser->read(session('discord_user')->id);
  if ($userExists === 'user doesnt exist') {
    $currentUser->create();
    $currentUser->read(session('discord_user')->id);
  }
  $currentUser->checkAvatar();
}
if ($currentUser->isLoggedIn() && !$currentUser->isVerified() && !findUrl("tos")) {
  header('Location: ' . WEBROOT . '/tos/');
}
