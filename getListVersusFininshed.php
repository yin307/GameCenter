<?php
include 'database.php';
$user_code = $_GET['user_code'] ? $_GET['user_code'] : "";
$game_id = $_GET['game_id'] ? $_GET['game_id'] : "";

$result['errorCode'] = 100;
$result['msg'] = "missing parameter";
$result['data'] = array();

if($user_code != "" && $game_id != "")
{
	$query = "SELECT user.user_code, achievement.name, ";
	$query .= " (SELECT user_code from user where user.user_code = versus.user_code_B) as compeditor";
	$query .= ", CASE result WHEN 0 THEN 'none' WHEN 1 THEN 'win' WHEN 2 THEN 'lose' END as result";
	$query .= ", CASE versus.finished WHEN 1 THEN 'finished' WHEN 0 THEN 'not-finished' END as finished ";
	$query .= " FROM user, versus, achievement WHERE user.user_code = versus.user_code_A ";
	$query .= " AND versus.ach_id = achievement.ach_id ";
	$query .= " AND user.user_code = '$user_code' AND versus.game_id = $game_id";
	$query .= " AND versus.finished = 1";

	db_connect();

	$result['errorCode'] = 0;
	$result['msg'] = "success";
	$result['data'] = db_select_list($query);

	db_close();

}
print(json_encode($result));