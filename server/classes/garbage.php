<?php
class garbage {

	public function add() {
		global $db, $users, $user_id;

		if(!isLogged()) {
			echo "Вы должны быть авторизованы на сайте для дальнейшей работы.";
		}

		$title = exst($_POST["title"]);
		if(is_uploaded_file($_FILES["photo"]["tmp_name"])) {
		     move_uploaded_file($_FILES["photo"]["tmp_name"], $_SERVER['DOCUMENT_ROOT']."uploads/".$_FILES["photo"]["name"]);
		     $image = "/uploads/".$_FILES["photo"]["name"];
		} else {
		    echo "Ошибка загрузки файла.";
		}
		$coord_x = exst($_POST["coord_x"]);
		$coord_y = exst($_POST["coord_y"]);
		$city = exst($_POST["city"]);
		$street = exst($_POST["street"]);
		$house = (int) $_POST["house"];
		$description = exst($_POST["description"]);
		$cleaningtime = exst($_POST["cleaningtime"]);
		// $uid = exst($_POST["uid"]); // TODO: Сделать работу только через токен доступа воизбежании подмен uid
		
		// TODO: Проверки на наличие

		if($db->insert('garbage', array(
		    'id' => NULL,
		    'maintitle' => $title,
		    'image' => $image,
		    'coord_x' => $coord_x,
		    'coord_y' => $coord_y,
		    'city' => $city,
		    'street' => $street,
		    'house' => $house,
		    'description' => $description,
		    'addtime' => time(),
		    'cleaningtime' => $cleaningtime,
		    'uid' => $user_id,
		    'active' => 0,
	   	))) {
		    echo "Всё отлично, Вы будете перенаправлены на главную страницу через 5 секунд.";
	   	} else {
	   		echo $db->error();
	    	echo 'Ошибка соединения с сервером. Попробуйте позже.';
	   	}
	}
	public function get($id = null) {
		global $db;
		if ($id == null)
			return $db->assoc($db->query("SELECT * FROM `garbage`"));
		else
			return $db->assoc($db->query("SELECT * FROM `garbage` WHERE `id` = '$id'"));
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