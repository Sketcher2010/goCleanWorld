<?php
$root = $_SERVER['DOCUMENT_ROOT'];

require($root.'/server/classes/db.php');
include($root.'/server/preloading.php');
include($root.'/server/functions.php');
require($root.'/server/classes/garbage.php');

echo $garbage->get();
?>