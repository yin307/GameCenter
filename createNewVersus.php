<?php
include 'database.php';
$user_code_A = $_GET['user_code_A'] ? $_GET['user_code_A'] : "";
$user_code_B = $_GET['user_code_B'] ? $_GET['user_code_B'] : "";
$game_id = $_GET['game_id'] ? $_GET['game_id'] : "";
$ach_name = $_GET['ach_name'] ? $_GET['ach_name'] : "";

$result['errorCode'] = 100;
$result['msg'] = 'error';
$result['data'] = array();

if($user_code_A == "" || $user_code_B == "" || $game_id == "" || $ach_name == "")
{
	$result['msg'] = 'missing parameter';
}
else
{
	db_connect();

	$ach_id = "SELECT ach_id from achievement WHERE name = '$ach_name' AND game_id = $game_id";
	//echo $ach_id;
	$ach_id = db_select_row($ach_id);

	if(!isset($ach_id['ach_id']))
	{
		$result['errorCode'] = 100;
		$result['msg'] = 'invalid ach_name';
	}
	else
	{
		$ach_id = $ach_id['ach_id'];
		$checkAch = "SELECT ach_com_id FROM achievement_complete WHERE user_code = '$user_code_A' AND ach_id = $ach_id";
		$checkAch = db_select_row($checkAch);
		if(isset($checkAch['ach_com_id']))
		{
			$result['errorCode'] = 100;
			$result['msg'] = "user completed this atrribute, cant versus";
			$data = array();
		}
		else
		{
			$checkVersus = "SELECT vs_id FROM versus WHERE user_code_A = '$user_code_A' AND user_code_B = '$user_code_B' AND finished = 0 AND ach_id = $ach_id";
			$checkVersus = db_select_row($checkVersus);

			$result['data'] = array('user_code_A' => $user_code_A, 'user_code_B' => $user_code_B, 'game_id' => $game_id, 'ach_name' => $ach_name);
			$result['errorCode'] = 0;
			$result['msg'] = 'success';


			if(!isset($checkVersus['vs_id']))
			{

				$data_insert = array(
				'user_code_A' => $user_code_A,
				'user_code_B' => $user_code_B,
				'ach_id' => $ach_id,
				'game_id' => $game_id,
				'created' => "NOW()"
					);
				db_insert("versus", $data_insert);
			}
		}
	}
}

db_close();
print(json_encode($result));