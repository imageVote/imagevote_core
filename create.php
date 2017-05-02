<?php

include 'upload.php';


//for public - and private too
//$arr = json_decode($value . "]");
//if (count($arr) > 4) { //q, opts, style, usrs
//    echo "_wrong creation data";
//    return;
//}

$value = $_POST["value"];

require_once 'sql/sql_create.php';
$table = null;
if (isset($_POST["table"])) {
    $table = $_POST["table"];
}
$id = sql_create($value, $table);

require "convBase.php";
$key = convBase($id, $base10, $base);
if (null == $table || "private" == $table) { //TODO: if private poll:
    $key = "-$key" . substr(rtrim(base64_encode($key), '='), -1);
}else{
    $key = "$table-$key";
}

require_once 'ali/ali_append.php';
ali_append($key, $value, $table);


echo $key;
