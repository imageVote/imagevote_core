<?php

include 'upload.php';


$data = $_POST["data"];

require_once 'sql/sql_create.php';
$table = "private";
if (isset($_POST["table"])) {
    $table = $_POST["table"];
}
$id = sql_create($data, $table);

require "convBase.php";
$key = convBase($id, $base10, $base);
if (null == $table || "private" == $table) { //TODO: if private poll:
    $key = "-$key" . substr(rtrim(base64_encode($key), '='), -1);
}else{
    $key = "$table-$key";
}

require_once 'ali/ali_append.php';
ali_append($key, $data, $table);


echo $key;
