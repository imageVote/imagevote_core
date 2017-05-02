<?php

$base = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
$base10 = '0123456789';

$id = null;
if (isset($_POST["id"])) {
    $key = $_POST["id"];
    require "convBase.php";
    $id = convBase($key, $base, $base10);
}

$lastId = null;
if (isset($_POST["lastId"])) {
    $lastId = $_POST["lastId"];
}

$data = null;
if (isset($_POST["table"])) {
    $table = $_POST["table"];
    require "sql/sql_select.php";
    $data = sql_select($table, $id, $lastId);
}

if (null != $data) {
    echo json_encode($data);
    die();
}

//not in db

