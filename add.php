<?php

require 'upload.php';
require "convBase.php";

$table = "private";
if (isset($_POST["table"])) {
    $table = $_POST["table"];
}

//if (isset($_POST["idQ"])) {
//    $id = $_POST["idQ"];
//    if (isset($_POST["id"])) {
//        $key = $_POST["id"];
//    } else {
//        $key = convBase($id, $base10, $base);
//        if ("private" != $table) {
//            $key = "$table-$key";
//        }
//    }
//    //
//}
//else 
if ($_POST["key"]) {
    $key = $_POST["key"];
    $keyArr = explode("-", $key);
    if (count($keyArr) > 1 && !empty($keyArr[0])) {
        $table = $keyArr[0];
    }
    if ("private" == $table) { //if private
        $key64 = substr($key, 1, -1);
    } else {
        $key64 = $keyArr[count($keyArr) - 1];
    }
    $id = convBase($key64, $base, $base10);
}

//android ERROR:
//require 'sql/sql_update.php';
//$add = json_decode($_POST["add"]);
//$sub = array();
//if (isset($_POST["sub"])) {
//    $sub = json_decode($_POST["sub"]);
//}
//sql_update($table, $id, $add, $sub);
//
//
//TODO: CHECK USER ID IS CORRECT!
//wrong versions code
$data = $_POST["data"];
if (!strpos($_POST["data"], "|")) {
    $data = $_POST["userId"] . "|" . $_POST["data"];
}

require 'ali/ali_append.php';
ali_append($key, PHP_EOL . $data, $table);


//use echo check to retrieve errors!
echo $key;
