<?php

$base = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
$base10 = '0123456789';

$key = $_POST["id"];

$data = null;
if (isset($_POST["table"])) {
    require "convBase.php";
    $id = convBase($key, $base, $base10);

    $table = $_POST["table"];
    require "sql/sql_select.php";
    $data = sql_select($table, $id);    
}

if(null != $data){
    echo $data;
    die();
}

//not in db

