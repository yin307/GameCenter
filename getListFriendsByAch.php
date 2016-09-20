<?php
//http://123.30.208.94/GameCenter/getListFriendsByAch.php?user_code=345345&attr_name=Level_1_star&game_id=1
include 'database.php';
$user_code = $_GET['user_code'] ? $_GET['user_code'] : '';
$game_id = $_GET['game_id'] ? $_GET['game_id'] : '';
$ach_name = $_GET['ach_name'] ? $_GET['ach_name'] : '';
$limit = $_GET['limit'] ? $_GET['limit'] : '';

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
		$query = "SELECT user.user_code,user.user_name, achievement.name, achievement_complete.score_completed ";
		$query .= " FROM user, achievement, achievement_complete ";
		$query .= " WHERE user.user_code = achievement_complete.user_code ";
		$query .= " and achievement.ach_id = achievement_complete.ach_id ";
		$query .= " and user.user_code in (SELECT user_code_B from user, friends where user.user_code = friends.user_code_A and user.user_code=$user_code)";
		$query .= " and achievement.name = '".$value."'";
		$query .= " and achievement_complete.game_id = $game_id order by achievement_complete.score_completed desc ";

		if ($limit != "")
		{
			$query .=  "LIMIT $limit";
		}
		//echo $query;
		$result_sub = db_select_list($query);
		//print_r($result);
		$data[] = $result_sub;
	}
	
	$result['errorCode'] = 0;
	$result['msg'] = 'success';
	$result['data'] = $data;
}
print(json_encode($result));
db_close();


