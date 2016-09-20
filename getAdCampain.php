<?php
include 'database.php';
$errorCode = 0;
$msg = "success";
$data = array();
$filter = $_GET['filter'] ? $_GET['filter'] : "";
if ($filter != "")
{
	$where = "";
	$filter = explode(",", trim($filter,","));
	foreach ($filter as $key => $value) {
		# code...
		$where .= " AND ".$value;
	}
}

db_connect();
$query = "SELECT * FROM ad_campain WHERE start_time <= NOW() AND NOW() <= end_time ".$where;
//echo $query;
$query = db_select_list($query);
foreach ($query as $key => $value) {
	$game_ads = [];
	$game_ad_ids = trim($value['order_game'], ',');
	$game_ad_ids = explode(",", $game_ad_ids);
	$type = $value['type'];
	$game_ad_id = $game_ad_ids[rand(0, sizeof($game_ad_ids) - 1)];
	$game_ad = "SELECT name, description, link_chplay, link_appstore, $type FROM game_ad WHERE id = '".$game_ad_id."'";
	$game_ads = db_select_row($game_ad);
	$data[] = array(
		'name' => $value['name'],
		'description' => $value['description'],
		'game_ads' => $game_ads,
		'type' => $value['type'],
		'os_target' => $value['os_target']
		);
}
$result = array(
	'errorCode' => $errorCode, 
	'msg' => $msg,
	'data' => $data
	);
db_close();
//print_r($result);
printf(json_encode($result));