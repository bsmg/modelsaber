<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', 300); //300 seconds = 5 minutes. In case if your CURL is slow and is loading too much (Can be IPv6 problem)
error_reporting(E_ALL);
require_once 'sessionStart.php';
$authorizeURL = 'https://discordapp.com/api/oauth2/authorize';
$tokenURL = 'https://discordapp.com/api/oauth2/token';
$apiURLBase = 'https://discordapp.com/api/users/@me';
$revokeURL = 'https://discordapp.com/api/oauth2/token/revoke';
$userURL = "https://discordapp.com/api/users/";
$prefix = '';
if (ENV == 'local') {
//  $prefix = 'http://localhost';
}
if(get('action') == 'login') {
  $params = array(
    'client_id' => OAUTH2_CLIENT_ID,
    'redirect_uri' => $prefix . WEBROOT,
    'response_type' => 'code',
    'scope' => 'identify',
    'prompt' => 'none'
  );
  // Redirect the user to Discord's authorization page
  header('Location: https://discordapp.com/api/oauth2/authorize' . '?' . http_build_query($params));
  die();
}
if(get('code')) {
    // Exchange the auth code for a token
    $token = apiRequest($tokenURL, array(
      "grant_type" => "authorization_code",
      'client_id' => OAUTH2_CLIENT_ID,
      'client_secret' => OAUTH2_CLIENT_SECRET,
      'redirect_uri' => $prefix . WEBROOT,
      'code' => get('code')
    ));
    $logout_token = $token->access_token;
    $_SESSION['access_token'] = $token->access_token;
    header('Location: ' . $_SERVER['PHP_SELF']);
  }
if(session('access_token')) {
  $_SESSION['discord_user'] = apiRequest($apiURLBase);
}
if(get('action') == 'logout') {
    apiRequest($revokeURL, array(
        'token' => session('access_token'),
        'client_id' => OAUTH2_CLIENT_ID,
        'client_secret' => OAUTH2_CLIENT_SECRET,
      ));
    unset($_SESSION['discord_user']);
    unset($_SESSION['access_token']);
    header('Location: ' . $_SERVER['PHP_SELF']);
    die();
  }
function apiRequest($url, $post=FALSE, $headers=array()) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($ch);
    if($post)
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    $headers[] = 'Accept: application/json';
    if(session('access_token'))
      $headers[] = 'Authorization: Bearer ' . session('access_token');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    return json_decode($response);
  }
  function get($key, $default=NULL) {
    return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
  }
  function session($key, $default=NULL) {
    return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
  }
  function getUser($id) {
    $curl = curl_init();
    
  $params = array(
      CURLOPT_URL => "https://discordapp.com/api/v6/users/$id",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => false,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_HTTPGET => true,
  );
  $headers[] = 'Accept: application/json';
  $headers[] = 'Authorization: Bot ' . OAUTH2_BOT_TOKEN;
  
  curl_setopt_array($curl, $params);
  $response = curl_exec($curl);
  $err = curl_error($curl);
  
  curl_close($curl);
  
  return $response;
}
?>
