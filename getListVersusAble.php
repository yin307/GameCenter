<?php
include 'database.php';


$user_code = $_GET['user_code'] ? $_GET['user_code'] : "";
$game_id = $_GET['game_id'] ? $_GET['game_id'] : "";
$ach_name = $_GET['ach_name'] ? $_GET['ach_name'] : "";
$limit = $_GET['limit'] ? $_GET['limit'] : "";

$result['errorCode'] = 100;
$result['msg'] = 'error';
$result['data'] = array();

if($user_code == '' || $game_id == '' || $ach_name == '')
{
	$result['msg'] = 'missing parameter';
}
else
{
	db_connect();
	$ach_name = explode(",", $ach_name);
	$data = array();
	foreach ($ach_name as $key => $value) {
		# code...
		$query = "SELECT user_code_B,  '$value' as ach_name 
					FROM friends f
					WHERE user_code_B NOT 
					IN (
					SELECT user_code_B
					FROM versus, achievement
					WHERE user_code_A =  '$user_code'
					AND versus.ach_id = achievement.ach_id
					AND achievement.name =  '$value'
					) order by rand()  ";
		if($limit != "")			
		{
			$query .= " limit $limit";
		}
		$result_sub = db_select_list($query);
		$data[] = $result_sub;
	}
	$result['errorCode'] = 0;
	$result['msg'] = 'success';
	$result['data'] = $data;

}

print(json_encode($result));
db_close();
