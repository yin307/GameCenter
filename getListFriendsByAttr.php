<?php
//http://123.30.208.94/GameCenter/getListFriendsByAttr.php?user_code=345345&attr_name=Level_1_star&game_id=1
include 'database.php';
$user_code = $_GET['user_code'] ? $_GET['user_code'] : '';
$game_id = $_GET['game_id'] ? $_GET['game_id'] : '';
$attr_name = $_GET['attr_name'] ? $_GET['attr_name'] : '';
if($user_code == '' || $game_id == '' || $attr_name == '')
{
print('error');die;
}
$query = "SELECT user.user_code, atrribute.name, atrribute_complete.score_completed ";
$query .= " FROM user, atrribute, atrribute_complete ";
$query .= " WHERE user.user_id = atrribute_complete.user_id ";
$query .= " and atrribute.att_id = atrribute_complete.att_id ";
$query .= " and user.user_id in (SELECT user_id_B from user, friends where user.user_id = friends.user_id_A and user.user_code=$user_code)";
$query .= " and atrribute.name = '$attr_name'";
$query .= " and atrribute_complete.game_id = $game_id";
db_connect();
$res = db_select_list($query);
print(json_encode($res));
db_close();