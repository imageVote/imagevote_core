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
        $key = "{$table}_{$key}";
    } else {
        $key = "-$key" . substr(rtrim(base64_encode($key), '='), -1);
    }
}

//TODO: CHECK USER ID IS CORRECT!
//wrong versions code
$previous_length = null;
if (isset($_POST["add"])) {
    $file_data = $_POST["userId"] . "|" . $_POST["add"];

    require 'ali/ali_append.php';
    $previous_length = ali_append($key, $file_data . PHP_EOL, $table);
}

//IF DID NOT EXIST -> create in sql
if (0 === $previous_length || (empty($previous_length) && empty($key))) {
    if (isset($_POST["data"])) {
        require 'sql/sql_create.php';
        sql_create($_POST["data"], $table, $id);
    }
}

//use echo check to retrieve errors!
echo $key;
