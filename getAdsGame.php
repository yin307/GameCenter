<?php
include 'database.php';
$errorCode = 0;
$msg = "success";
$data = array();
db_connect();
$type = $_GET['type'] ? $_GET['type'] : "";
if($type == ""){
	$type = "banner,icon,full_screen";
}else{
	$type = trim($type,",");
}

$type = explode(",", $type);
//print_r($type);
foreach ($type as $key => $value) {
	# code...
	$query = "SELECT ".$value.", name,description,link_chplay,link_appstore FROM game_ad ORDER BY rand() LIMIT 1";
	//echo $query;
	$data [$value] = db_select_row($query);
}
$result = array(
	"errorCode" => $errorCode,
	"msg" => $msg,
	"data" => $data
	);

db_close();
//print_r($result);
printf(json_encode($result));