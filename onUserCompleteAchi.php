<?php
include 'database.php';
$user_code = $_GET['user_code'] ? $_GET['user_code'] : '';
$game_id = $_GET['game_id'] ? $_GET['game_id'] : '';
$ach_name = $_GET['ach_name'] ? $_GET['ach_name'] : '';
$score_completed = $_GET['score_completed'] ? $_GET['score_completed'] : '';

$errorCode = 100;
$msg = 'error';
$data = array();
db_connect();
if($ach_name == '' || $game_id == '' || $user_code == '' || $score_completed == '')
{
	$error = 100;
	$msg = 'mssing parameter';
	$data = array();
}
else
{
	$ach_id = "SELECT ach_id from achievement WHere name = '$ach_name' and game_id = $game_id";
	$ach_id = db_select_row($ach_id);

	if(!isset($ach_id['ach_id']))
	{
		$error = 100;
		$msg = 'invalid ach_name';
		$data = array();
	}
	else
	{
		$ach_id = $ach_id['ach_id'];
		$ach_com_id = "SELECT ach_com_id FROM achievement_complete WHere ach_id = $ach_id and game_id = $game_id and user_code = '$user_code'";
		$ach_com_id = db_select_row($ach_com_id);

		if(!isset($ach_com_id['ach_com_id']))
		{
			$data = array(
				'user_code' => $user_code,
				'game_id' => $game_id,
				'ach_id' => $ach_id,
				'score_completed' => $score_completed,
				'created' => 'NOW()'
				);
			db_insert('achievement_complete', $data);
			$error = 0;
			$msg = "success";

		}
		else
		{
			$ach_com_id = $ach_com_id['ach_com_id'];
			$data = array(
				'user_code' => $user_code,
				'game_id' => $game_id,
				'ach_id' => $ach_id,
				'score_completed' => $score_completed,
				'modified' => 'NOW()'
				);
			db_update_by_id('achievement_complete', 'ach_com_id', $ach_com_id, $data);
			$error = 0;
			$msg = "success";
		}
		updateVersus($user_code, $ach_id, $game_id);
	}
}
db_close();
$result = array(
'errorCode' => $errorCode,
'msg' => $msg,
'data' => $data
	);
function updateVersus($user_code='', $ach_id='',$game_id='')
{
	# code...
	$queryGet = "SELECT vs_id, user_code_A, user_code_B FROM versus";
	$queryGet .= " WHERE user_code_A in (SELECT user_code_A FROM achievement_complete WHERE ach_id = $ach_id and game_id = $game_id)"; 
	$queryGet .= " AND user_code_B in (SELECT user_code_B FROM achievement_complete WHERE ach_id = $ach_id and game_id = $game_id)"; 
	$queryGet .= " AND game_id = $game_id AND user_code_A = '$user_code' AND ach_id = $ach_id";

	$list = db_select_list($queryGet);
	//print_r($list);
	foreach ($list as $key => $value) {
		# code...
		$user_code_A = $value['user_code_A'];
		$user_code_B = $value['user_code_B'];
		$scoreA = db_select_row("SELECT score_completed from achievement_complete WHERE user_code = '$user_code_A' AND ach_id = $ach_id AND game_id = $game_id")['score_completed'];
		$scoreB = db_select_row("SELECT score_completed from achievement_complete WHERE user_code = '$user_code_B' AND ach_id = $ach_id AND game_id = $game_id")['score_completed'];
		//echo $scoreA."  ".$scoreB;
		if ($scoreA > $scoreB)
		{
			$result = 1;
		}
		else
		{
			$result = 2;
		}
		$data_versus = array('result' => $result,'finished' => 1,'modified' => 'NOW()' );
		db_update_by_id('versus','vs_id', $value['vs_id'], $data_versus);
	}
}
print(json_encode($result));