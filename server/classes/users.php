<?php
class users {
 public function checkLogin($login = null) {
  global $db;

  $q = $db->query("SELECT COUNT(*) as `count` FROM `users` WHERE `login` = '".$db->escape($login)."'");
  $d = $db->assoc($q);

  return (($d['count']) ? true : false);
 }

 public function auth() {
  global $db, $time, $user_agent, $ip_address;

  $login = isset($_POST['login']);
  $password = isset($_POST['password']);

  $q = $db->query("SELECT `id`, `password`, `hash` FROM `users` WHERE `login` = '$login'");
  $d = $db->assoc($q);

  $user_id = $d['id'];
  $db_password = $d['password'];
  $hash = $d['hash'];

  if($user_id) {
   if($db_password == hash("sha512", $password)) {
    $this->insertAuthLog($user_id);

    $this->authCookies($user_id, $hash);

    $error = array('response' => 1);
   } else {
    $error = array('error_msg' => 'Неправильный логин или пароль.');
   }
  } else {
   $error = array('error_msg' => 'Пользователь не существует.');
  }

  return json_encode($error);
 }

 public function reg() {
  global $db, $time, $user_agent, $ip_address;

  $login = exst($_POST['login']);
  $password1 = exst($_POST['password1']);
  $password = $password1;
  $email = exst($_POST['email']);

  $hash = md5(md5($login.'+'.$password.'+'.$time));
  if(!isEmail($email)) {
   $error = array('field_name' => 'reg_email', 'error_msg' => 'Проверьте корректность e-mail.');
  } elseif(!isLogin($login)) {
   $error = array('field_name' => 'reg_login', 'error_msg' => 'Логин может содержать только латинские символы, некоторые знаки или цифры и не превышать 2-20 символов.');
  } elseif($this->checkLogin($login)) {
   $error = array('field_name' => 'reg_login', 'error_msg' => 'К сожалению, но логин <b>'.$login.'</b> уже занят.');
  } elseif(!isPassword($password)) {
   $error = array('field_name' => 'password1', 'error_msg' => 'Пароль может содержать только латинские символы, некоторые знаки или цифры и не превышать 6-30 символов.');
  } else {
   if($db->insert('users', array(
    'id' => NULL,
    'login' => $login,
    'password' => hash("sha512", $password),
    'email' => $email,
    'active' => 0,
    'hash' => $hash,
    'ip_address' => $ip_address,
    'user_agent' => $user_agent,
    'regtime' => $time,
    'lasttime' => $time,
    'group' => 0,
   ))) {
    $user_id = $db->insertId();

    $this->authCookies($user_id, $hash);

    $_SESSION['captcha_code'] = '';

    $error = array('response' => 1);
   } else {
    $error = array('error_msg' => 'Ошибка соединения с сервером. Попробуйте позже.');
   }
  }

  return json_encode($error);
 }

 public function authCookies($user_id = null, $hash = null) {
  setCookie('user_id', $user_id, time() + 60 * 60 * 24 * 365, '/');
  setCookie('auth_hash', $hash, time() + 60 * 60 * 24 * 365, '/'); 
 }

 public function logout($hash = null) {
  global $user_logged_in, $user_hash;

  if($user_logged_in && $hash == $user_hash) {
   setCookie('user_id', '', 0, '/');
   setCookie('auth_hash', '', 0, '/');

   return true;
  }
 }
}

$users = new users;
?>