<?php

$table = $_POST["table"];

$id = null;
if (isset($_POST["id"])) {
    $id = $_POST["id"];
}
$lastId = null;
if (isset($_POST["lastId"])) {
    $lastId = $_POST["lastId"];
}
$keyId = null;
if (isset($_POST["key"])) {
    $keyId = $_POST["key"];
}
$arrIds = null;
if (isset($_POST["arrIds"])) {
    $arrIds = $_POST["arrIds"];
}

require "sql/sql_select.php";
$data = sql_select($table, $id, $lastId, $arrIds);

//update sql from file (only 1 poll)
if (null !== $id || null !== $keyId) {
    require 'fileToSql.php';
    $updated = false;

    $row = $data[0];
    if (($row["v0"] + $row["v1"]) < 100 || time() - strtotime($row['t']) > 86400) { //24h
        fileToSql($table, $id, $keyId);
        $updated = true;
    }

    if ($updated) {
        $data = sql_select($table, $id, $lastId);
    }
}

//get sql data:
echo json_encode($data);
