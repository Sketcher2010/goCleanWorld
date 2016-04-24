<?php
$root = $_SERVER['DOCUMENT_ROOT'];

require($root.'/server/classes/db.php');
include($root.'/server/preloading.php');
include($root.'/server/functions.php');
require($root.'/server/classes/garbage.php');

if($_GET["id"] > 0) {
	echo json_encode($garbage->get((int)$_GET["id"]));
} else {
	echo json_encode($garbage->get());
}
?>