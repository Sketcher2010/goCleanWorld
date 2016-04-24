<?php
class garbage {

	public function add() {
		global $db, $users;

		if(!isLogged()) {
			return json_encode(array("error_msg" => "Вы должны быть авторизованы на сайте для дальнейшей работы."));
		}

		$title = exst($_POST["title"]);
		$image = exst($_POST["image"]);
		$coord_x = exst($_POST["coord_x"]);
		$coord_y = exst($_POST["coord_y"]);
		$cleaningtime = exst($_POST["cleaningtime"]);
		$uid = exst($_POST["uid"]); // TODO: Сделать работу только через токен доступа воизбежании подмен uid
		
		// TODO: Проверки на наличие

		if($db->insert('garbage', array(
		    'id' => NULL,
		    'title' => $title,
		    'image' => $image,
		    'coord_x' => $coord_x,
		    'coord_y' => $coord_y,
		    'addtime' => time(),
		    'cleaningtime' => $cleaningtime,
		    'uid' => $uid,
		    'active' => 0,
	   	))) {
		    $error = array('response' => 1);
	   	} else {
	    	$error = array('error_msg' => 'Ошибка соединения с сервером. Попробуйте позже.');
	   	}
	   	return json_encode($error);
	}
	public function get($id = null) {
		global $db;
		if ($id == null)
			return json_encode($db->assoc($db->query("SELECT * FROM `garbage`")));
		else
			return json_encode($db->assoc($db->query("SELECT * FROM `garbage` WHERE `id` = '$id'")));
	}
	public function visit() {
		global $db, $users, $user_id;

		if(!isLogged()) {
			return json_encode(array("error_msg" => "Вы должны быть авторизованы на сайте для дальнейшей работы."));
		}

		$gid = (int) $_POST["gid"]; // TODO: Проверка наличия этого события

		if($db->insert('visits', array(
		    'uid' => $user_id,
		    'gid' => $gid
	   	))) {
		    $error = array('response' => 1);
	   	} else {
	    	$error = array('error_msg' => 'Ошибка соединения с сервером. Попробуйте позже.');
	   	}
	   	return json_encode($error);
	}

	public function countVisitors($id = null) {
		global $db;

		return $db->fetch($db->query("SELECT COUNT(*) FROM `visits` WHERE `gid` = '$id'"))[0];
	}
}

$garbage = new garbage;
?>