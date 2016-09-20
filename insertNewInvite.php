<?php
include 'database.php';
$user_code_A = $_GET['user_code_A'] ? $_GET['user_code_A'] : "";
$user_code_B = $_GET['user_code_B'] ? $_GET['user_code_B'] : "";
$game_id = $_GET['game_id'] ? $_GET['game_id'] : "";

$result['errorCode'] = 100;
$result['msg'] = "missing parameter";
$result['data'] = array();

if($user_code_A != "" && $user_code_B != "" && $game_id != "")
{
	//$query_check = "SELECT "
	$result['data'] = array('user_code_A' => $user_code_A, 'user_code_B' => $user_code_B, 'game_id' => $game_id, 'status' => 0, 'created' => 'NOW()', 'rewarded' => 0);
	db_connect();
	db_insert('invite_friends', $result['data']);
	db_close();
	$result['errorCode'] = 0;
	$result['msg'] = "success";
}
printf(json_encode($result));