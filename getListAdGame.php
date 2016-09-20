<?php
include 'database.php';
db_connect();
$query = 'SELECT name, icon, link_chplay, link_appstore FROM game_ad';
$list = db_select_list($query);


$index = rand(0, sizeof($list) - 1);
$res = $list[$index]['icon'].'|';

$res .= $list[$index]['link_appstore'].'|';

$res .= $list[$index]['link_chplay'];

print($res);
db_close();
