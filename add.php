<?php

include 'upload.php';

$key = $_POST["key"];
if(true){ //if private
    $key64 = substr($key, 1, -1);
}

if ($public) {
    $arr = explode("|", $value);
    if ($arr[0] != $userId) {
        echo "_wrong user id: $arr[0] != $userId";
        die();
    }
}

require_once 'sql/sql_update.php';
$add = json_decode($_POST["add"]);
$remove = json_decode($_POST["remove"]);

require_once("convBase.php");
$id = convBase($key64, $base, $base10);
sql_update("private", $id, $add, $remove);

require_once 'ali/ali_append.php';
ali_append($key, PHP_EOL . $value, $visibility);


echo $key;
