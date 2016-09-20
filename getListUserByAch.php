<?php
include 'database.php';
$ach_name = $_GET['ach_name'] ? $_GET['ach_name'] : '';
$game_id = $_GET['game_id'] ? $_GET['game_id'] : "";
$limit = $_GET['limit'] ? $_GET['limit'] : "";
$result['errorCode'] = 100;
$result['msg'] = 'error';
$result['data'] = array();

if($ach_name == '' || $game_id == '')
{
	$result['msg'] = 'missing parameter';
}
else
{
	db_connect();
	$data = array();
	foreach ($variable as $key => $value) {
		# code...
		$query = "SELECT user.user_code, achievement.name as ach_name, achievement_complete.score_completed ";
		$query .= " FROM user, achievement, achievement_complete ";
		$query .= " WHERE user.user_code = achievement_complete.user_code and achievement.ach_id = achievement_complete.ach_id ";
		$query .= " AND achievement.name = '$value'";
		$query .= " AND achievement_complete.game_id = $game_id";
		$query .= " ORDER BY achievement_complete.score_completed DESC ";

		if($limit != "")
		{
			$query .= " LIMIT $limit";
		}

		$data[] = db_select_list($query);
		$result['errorCode'] = 0;
		$result['msg'] = 'success';
		$result['data'] = $data;
	}
}
print(json_encode($result));
db_close();