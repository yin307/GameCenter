<?php

include 'database.php';
$user_code = $_GET['user_code'] ? $_GET['user_code'] : '';
$game_id = $_GET['game_id'] ? $_GET['game_id'] : '';

$result['errorCode'] = 100;
$result['msg'] = 'error';
$result['data'] = array();

if($user_code == '' || $game_id == '')
{
	$result['msg'] = "missing parameter";
}
else
{

	$query = "SELECT user.user_code, user.user_name FROM user,user_play WHERE user.user_code = user_play.user_code ";
	$query .= " and user_play.game_id = $game_id and user.user_code in (SELECT friends.user_code_B FROM user,friends WHERE ";
	$query .= " user.user_code = friends.user_code_A and user.user_code = $user_code)";

	db_connect();
	$result['errorCode'] = 0;
	$result['msg'] = 'success';
	$result['data'] = db_select_list($query);
}
print(json_encode($result));
db_close();