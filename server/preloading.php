<?
date_default_timezone_set('Europe/Moscow');
$http_host = $_SERVER['HTTP_HOST'];
$user_agent = getenv('HTTP_USER_AGENT');

$file_version = rand();

$time = time();

$ip_address = $_SERVER['REMOTE_ADDR'];

$user_id = isset($_COOKIE['user_id']) ? (int) $_COOKIE['user_id'] : '';
$auth_hash = isset($_COOKIE['auth_hash']) ? $_COOKIE['auth_hash'] : '';

$user_online_limit = $time - (15 * 60);


$user_info = $db->query("
 SELECT * FROM `users` WHERE `id` = '$user_id'");

$user_data = $db->assoc($user_info);

if(count($user_data)) {
 foreach($user_data as $key => $value) {
  ${'user_'.$key} = $value;
 }

function isLogged() {
  global $user_hash, $auth_hash;

  if ($user_hash == $auth_hash && $auth_hash) {
    return true;
  }
  else {
    return false;
  }
}
 // помечаем аккаунт в Online
 if(isLogged() && $user_lasttime < $user_online_limit) {
  $db->update('users', array('lasttime' => $time), array('id' => $user_id));
 }
} else {
 $user_logged_in = 0;
}
?>