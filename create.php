<?php

include 'upload.php';


//for public - and private too
$arr = json_decode($value . "]");
if (count($arr) > 4) { //q, opts, style, usrs
    echo "_wrong creation data";
    return;
}

require_once 'sql/sql_create.php';
$id = sql_create("in", $value);

require_once("convBase.php");
$key = convBase($id, $base10, $base);

require_once 'ali/ali_append.php';
ali_append($key, $value, $visibility);


echo $key;