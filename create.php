<?php

include 'upload.php';


$data = $_POST["data"];

require_once 'sql/sql_create.php';
$table = "private";
if (isset($_POST["table"])) {
    $table = $_POST["table"];
}
$id = sql_create($data, $table);

require_once 'ali/ali_append.php';
ali_append($id, $data, $table);


echo $id;
