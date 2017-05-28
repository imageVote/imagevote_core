<?php

require 'upload.php';
require "convBase.php";

$table = null;
if (isset($_POST["table"])) {
    $table = $_POST["table"];
}

$key = null;
if (isset($_POST["key"])) {
    $key = $_POST["key"];
}
$id = null;
if (isset($_POST["idQ"])) {
    $id = $_POST["idQ"];
}

if (!empty($key)) {
    if (!$id) {
        require_once 'idKey.php';
        $id = idKey($key);
    }
    //
} else {
    if (!$id) {
        $data = $_POST["data"];
        require 'sql/sql_create.php';
        $id = sql_create($data, $table);
    }

    $key = convBase($id, $base10, $base);
    if (!empty($table)) {
        $key = "$table-$key";
    }
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
$previous_length = null;
if (isset($_POST["add"])) {
    $data = $_POST["userId"] . "|" . $_POST["add"];

    require 'ali/ali_append.php';
    $previous_length = ali_append($key, PHP_EOL . $data, $table);
}

//IF DID NOT EXIST -> create in sql
if (0 === $previous_length || (empty($previous_length) && empty($key))) {
    if (isset($_POST["sql_data"])) {
        require 'sql/sql_create.php';
        sql_create($_POST["sql_data"], $table, $id);
    }
}

//use echo check to retrieve errors!
echo $key;
