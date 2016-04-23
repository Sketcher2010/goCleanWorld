<?php
// склонение числительных
function declOfNum($number = null, $titles = null) {
 $cases = array(2, 0, 1, 1, 1, 2);

 return $titles[($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)]];
}
// fix Notice: Undefined index 
function exst(&$var = null) {
 return ((!isset($var) || !$var) ? null : $var);
}

// проверка правильности логина
function isLogin($login = null) {
 return ((preg_match('/^([@a-zA-Z0-9\-\.\_\*]){2,20}$/is', $login)) ? 1 : 0);
}

// проверка правильности пароля
function isPassword($password = null) {
 return ((preg_match('/^([@a-zA-Z0-9\-\.\_\*]){6,32}$/is', $password)) ? 1 : 0);
}

// проверка правильности email
function isEmail($email = null) {
 return ((preg_match('/^[a-zA-Z0-9\.\-\_]+@([a-zA-Z0-9\-\_]+\.)+[a-zA-Z]{2,6}$/is', $email)) ? 1 : 0);
}

// красивое отображение массива
function printR($array = null) {
 echo '<pre>';
  print_r($array);
 echo '</pre>';
}

// Определение IP клиента
function ip_address() {
 if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip_result = $_SERVER['HTTP_X_FORWARDED_FOR'];
 elseif(isset($_SERVER['HTTP_CLIENT_IP'])) $ip_result = $_SERVER['HTTP_CLIENT_IP']; 
 else $ip_result = $_SERVER['REMOTE_ADDR'];
 return $ip_result;
}

// определение браузера пользователя
function user_browser() { 
  $str = getenv('HTTP_USER_AGENT'); 
  if(strpos($str, 'Avant Browser', 0) !== false) return 'Avant Browser'; 
  elseif(strpos($str, 'Acoo Browser', 0) !== false) return 'Acoo Browser'; 
  elseif(@eregi('Iron/([0-9a-z\.]*)', $str, $pocket)) return 'SRWare Iron '.$pocket[1];
  elseif(@eregi('Chrome/([0-9a-z\.]*)', $str, $pocket)) return 'Google Chrome '.$pocket[1]; 
  elseif(@eregi('(Maxthon|NetCaptor)( [0-9a-z\.]*)?', $str, $pocket)) return $pocket[1].$pocket[2];
  elseif(@strpos($str, 'MyIE2', 0) !== false) return 'MyIE2'; 
  elseif(@eregi('(NetFront|K-Meleon|Netscape|Galeon|Epiphany|Konqueror|'. 'Safari|Opera Mini)/([0-9a-z\.]*)', $str, $pocket)) return $pocket[1].' '.$pocket[2]; 
  elseif(@eregi('Opera[/ ]([0-9a-z\.]*)', $str, $pocket)) return 'Opera '.$pocket[1]; 
  elseif(@eregi('Orca/([ 0-9a-z\.]*)', $str, $pocket)) return 'Orca Browser '.$pocket[1]; 
  elseif(@eregi('(SeaMonkey|Firefox|GranParadiso|Minefield|'.'Shiretoko)/([0-9a-z\.]*)', $str, $pocket)) return 'Mozilla '.$pocket[1].' '.$pocket[2]; 
  elseif(@eregi('rv:([0-9a-z\.]*)', $str, $pocket) && strpos($str, 'Mozilla/', 0) !== false) return 'Mozilla '.$pocket[1]; 
  elseif(@eregi('Lynx/([0-9a-z\.]*)', $str, $pocket)) return 'Lynx '.$pocket[1];
  elseif(@eregi('MSIE ([0-9a-z\.]*)', $str, $pocket)) return 'Internet Explorer '.$pocket[1];
  else return 'Unknown';
}

// преобразовываем время в нормальный вид
function new_time($a) {
 date_default_timezone_set('Europe/Moscow');
 $ndate = date('d.m.Y', $a);
 $ndate_time = date('H:i', $a);
 $ndate_exp = explode('.', $ndate);
 $nmonth = array(
  1 => 'янв',
  2 => 'фев',
  3 => 'мар',
  4 => 'апр',
  5 => 'мая',
  6 => 'июн',
  7 => 'июл',
  8 => 'авг',
  9 => 'сен',
  10 => 'окт',
  11 => 'ноя',
  12 => 'дек'
 );
 
 foreach ($nmonth as $key => $value) {
  if($key == intval($ndate_exp[1])) $nmonth_name = $value;
 }
 
 if($ndate == date('d.m.Y')) return 'сегодня в '.$ndate_time;
 elseif($ndate == date('d.m.Y', strtotime('-1 day'))) return 'вчера в '.$ndate_time;
 else return $ndate_exp[0].' '.$nmonth_name.' '.$ndate_exp[2].' в '.$ndate_time;
}

?>