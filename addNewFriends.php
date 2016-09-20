<?php
include 'database.php';
$user_code_A = $_GET['user_code_A'] ? $_GET['user_code_A'] : "";
$user_code_B = $_GET['user_code_B'] ? $_GET['user_code_B'] : "";

$errorCode = 100;
$msg = 'error';
$data = array();
if($user_code_A == '' || $user_code_B == "")
{
	$errorCode = 100;
	$msg = 'missing parameter';
	$data = array();
}
else
{
	
	db_connect();
	$queryCheck = "SELECT user_code_A FROM friends WHERE user_code_A = '$user_code_A' AND user_code_B = '$user_code_B'";
	$check = db_select_row($queryCheck);
	$data = array('user_code_A' => $user_code_A, 'user_code_B' => $user_code_B, 'created' => 'NOW()' );

	if(isset($check['user_code_A']) && $check['user_code_A'] != '')
	{
		$errorCode = 0;
		$msg = 'success';
		//$data = array();
	}
	else
	{
		db_insert('friends', $data);
		$errorCode = 0;
		$msg = 'success';	
	}
}

$result = array(
'errorCode' => $errorCode,
'msg' => $msg,
'data' => $data
	);
print(json_encode($result));
db_close();