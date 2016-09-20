<?php
include 'database.php';
$atrr_name = $_GET['atrr_name'] ? $_GET['atrr_name'] : '';
$game_id = $_GET['game_id'] ? $_GET['game_id'] : "";
if($atrr_name == '' || $game_id == '')
{
	print("error");die;
}
$query = "SELECT user.user_code, atrribute.name as atrr_name, atrribute_complete.score_completed ";
$query .= " FROM user, atrribute, atrribute_complete ";
$query .= " WHERE user.user_id = atrribute_complete.user_id and atrribute.att_id = atrribute_complete.att_id ";
$query .= " AND atrribute.name = '$atrr_name'";
$query .= " AND atrribute_complete.game_id = $game_id";
$query .= " ORDER BY atrribute_complete.score_completed DESC";

db_connect();
$result = db_select_list($query);
print(json_encode($result));
db_close();