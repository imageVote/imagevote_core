<?php

require 'upload.php';

$table = null;
if (isset($_POST["table"])) {
    $table = $_POST["table"];
}

$id = null;
if (isset($_POST["id"])) {
    $id = $_POST["id"];
}else if (isset($_POST["idQ"])) {
    $id = $_POST["idQ"];
}

if (!$id) {
    $data = $_POST["data"];
    require 'sql/sql_create.php';
    $id = sql_create($data, $table);
}

//TODO: CHECK USER ID IS CORRECT!
//wrong versions code
$previous_length = null;
if (isset($_POST["add"])) {
    $file_data = $_POST["userId"] . "|" . $_POST["add"];

    require 'ali/ali_append.php';
    $previous_length = ali_append($id, $file_data . PHP_EOL, $table);
}

//IF DID NOT EXIST(bug?) -> create in sql
//if (0 === $previous_length || (empty($previous_length) && empty($id))) {
//    if (isset($_POST["data"])) {
//        require_once 'sql/sql_create.php';
//        sql_create($_POST["data"], $table, $id);
//    }
//}

//use echo check to retrieve errors!
echo $id;
