<?php
include './config.php';
$conn = null;

function db_connect()
{
    global $conn;

    $conn = mysql_connect("localhost", "root", "haanh@2015") or die('khong the ket noi den db aa');
    mysql_query("SET character_set_client=utf8", $conn);
    mysql_set_charset('utf8');
    mysql_select_db("GameCenter") or die('khong tim thay db');
    mysql_query("SET NAMES 'utf8'");
}

function db_select_list($query)
{
    global $conn;
    $result = mysql_query($query, $conn);
    if (!$result) {
        
    }

    $list = array();

    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $list[] = $row;
    }
    //echo $query;
    mysql_free_result($result);
    //var_dump($list);
    return $list;
}


function db_count($sql, $count_as)
{
    global $conn;
    $result = mysql_query($sql, $conn);
    if (!$result) {
        
    }

    $row = ysql_fetch_array($result, MYSQL_ASSOC);
    if ($row) {
        mysql_free_result($result);
        return $row[$count_as];
    }

    return 0;
}

function db_select_row($query)
{
    global $conn;
    $result = mysql_query($query, $conn);
    if (!$result) {
        //die;
    }

    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    mysql_free_result($result);
    return $row;
}

function db_insert($table, $data = array())
{
    global $conn;
    $filed = '';
    $value = '';

    foreach ($data as $key => $val) {
        $filed .= $key . ',';
        if($val == "NOW()")
        {
            $value .= mysql_escape_string($val) .',';
        }
        else
        {
            $value .= "'" . mysql_escape_string($val) . "'" . ',';    
        }
        

    }
    $query = 'INSERT INTO ' . $table . ' (' . trim($filed, ',') . ') VALUES(' . trim($value, ',') . ')';
   // print_r($conn)
    //echo $query;
    return mysql_query($query, $conn);
}

function db_update_by_id($table, $idfiled, $idvalue, $data = array())
{
    global $conn;

    $set = '';

    foreach ($data as $key => $val) {
        if($val == 'NOW()')
        {
            $set .= $key . '='  . mysql_escape_string($val) . ',';   
        }   
        else
        {
            $set .= $key . '=' . '\'' . mysql_escape_string($val) . '\',';
        }
    }
    $query = 'UPDATE ' . $table . ' SET ' . trim($set, ',') . ' WHERE ' . $idfiled . '=' . (int) $idvalue;
    return mysql_query($query, $conn);
}

function db_update_by_field($table, $filed = array(), $data = array())
{
    global $conn;

    $set = '';
    $condi = "";
    foreach ($data as $key => $val) {
        if($val == 'NOW()')
        {
            $set .= $key . '='  . mysql_escape_string($val) . ',';   
        }   
        else
        {
            $set .= $key . '=' . '\'' . mysql_escape_string($val) . '\',';
        }
    }
    foreach ($filed as $key => $value) {
        if($value == 'NOW()')
        {
            $condi .= $key . '='  . mysql_escape_string($value) . ' AND ';   
        }   
        else
        {
            $condi .= $key . '=' . '\'' . mysql_escape_string($value) . '\' AND ';
        }
    }
    $query = 'UPDATE ' . $table . ' SET ' . trim($set, ',') . ' WHERE ' . trim($condi, ' AND ');
    echo $query;
    return mysql_query($query, $conn);
}

function db_delete_by_id($table, $idfiled, $idvalue)
{
    global $conn;
    $query = 'DELETE FROM ' . $table . ' WHERE ' . $idfiled . '=' . (int) $idvalue;
    return mysql_query($query, $conn);
}

function db_close()
{
    global $conn;
    mysql_close($conn);
}

function db_filter($query, $data = array())
{
    global $conn;
    $query .= ' where 1';
    foreach ($data as $key => $val) {
        if (strlen($val) > 0)
            $query .= ' and ' . $key . '=' . '\'' . mysql_escape_string($val) . '\'';
    }
    //echo $query;
    $result = mysql_query($query, $conn);
    if (!$result) {
        //die;
    }

    $list = array();

    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $list[] = $row;
    }
    //echo $query;
    mysql_free_result($result);
    //var_dump($list);
    return $list;
}
?>

