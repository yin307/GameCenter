<?php
//ini_set('display_errors', 1);
include 'database.php';
$user_code = $_GET['user_code'] ? $_GET['user_code'] : '';
$user_name = $_GET['user_name'] ? $_GET['user_name'] : '';
$game_id = $_GET['game_id'] ? $_GET['game_id'] : '';

$errorCode = 100;
$msg = 'error';
$data = array();

if($user_code == "" || $user_name == "" || $game_id == "")
{
	$errorCode = 100;
	$msg = 'missing parameter';
	$data = array();
}
else
{
	$data = array(
	'user_code' => $user_code,
	'user_name'  => $user_name,
	'created' => 'NOW()'
	);
	db_connect();


	$insertUser = db_insert('user', $data);
	$userOnGame = db_select_row("SELECT user_code FROM user_play WHERE user_code = $user_code AND game_id = $game_id");
		
	if(!isset($userOnGame['user_code']))
	{
		$dataGame = array('user_code' => $user_code ,"game_id" => $game_id, "created" => "NOW()" );
		db_insert('user_play', $dataGame);
		$errorCode = 0;
		$msg = 'success';
	}
	else
	{
		$errorCode = 0;
		$msg = 'success';
	}
	updateInvite($game_id, $user_code);
	db_close();
}
$result = array(
'errorCode' => $errorCode,
'msg' => $msg,
'data' => $data
	);
print(json_encode($result));

function updateInvite($game_id='', $user_code='')
{
	# code...
	$queryCheck = "SELECT user_code_A from invite_friends where user_code_B = '$user_code' AND game_id=$game_id AND status = 0";

	$listInvite = db_select_list($queryCheck);

	foreach ($listInvite as $key => $value) {
		# code...
		// /echo 22222;
		$data_invite = array(
			"status" => 1
			);
		$field = array(
			"user_code_B" => $user_code,
			"user_code_A" => $value['user_code_A'],
			"game_id" => $game_id
			);
		db_update_by_field('invite_friends', $field, $data_invite);
	}
}
