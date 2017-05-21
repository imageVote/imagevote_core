<?php

$base = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
$base10 = '0123456789';
require "convBase.php";

function fileToSql($table, $id, $key = null) {
    global $base, $base10;
    
    $table_string = $table;
    if(!$table){
        $table_string = "private";
    }
    
    if (null == $key) {
        $key = convBase($id, $base10, $base);
        if ($table) {
            $key = "$table-$key";
        }
    }

    //select by line because public (configure ip CORS)
    $path = "http://wouldyourather-$table_string.oss-eu-central-1.aliyuncs.com/$key?nocache=" . rand();
    $fp = fopen($path, 'r');
    if (!$fp) {
        require_once 'sql/sql_select.php';
        $poll = sql_select($table, $id)[0];
        require_once 'ali/ali_append.php';
        ali_append($key, $poll['data'], $table);
        return;
    }

    $first = fgets($fp); //ignore first line

    $answers = array();
    while ($line = fgets($fp)) {
        $arr = explode("|", $line);
        $votes = json_decode($arr[1]);
        for ($i = 0; $i < count($votes); $i++) {
            $answers[$arr[0]] = (int) $votes[$i];
        }
    }
    
    $res = array(0, 0);
    foreach ($answers as $userId => $vote) {
        $res[$vote] ++;
    }

    $total = 0;
    $set = "";
    for ($i = 0; $i < count($res); $i++) {
        $set .= " v$i = $res[$i]";
        if (count($res) > $i + 1) {
            $set .= ",";
        }
        $total += $res[$i];
    }

    //update
    require_once 'sql/connect.php';
    $pdo = new PDO($connect, $user, $pass);
        
    $q = "UPDATE `$table_string` SET $set WHERE id = :id";
    $sth = $pdo->prepare($q) or die(implode(":", $sth->errorInfo()) . " in $q");
    $sth->bindParam(":id", $id);
    $sth->execute() or die(implode(":", $sth->errorInfo()) . " in $q");
}
