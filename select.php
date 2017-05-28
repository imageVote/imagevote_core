<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$table = "";
if (isset($_POST["table"])) {
    $table = $_POST["table"];
}

$id = null;
if (isset($_POST["id"])) {
    $id = $_POST["id"];
}
$lastId = null;
if (isset($_POST["lastId"])) {
    $lastId = $_POST["lastId"];
}
$arrIds = null;
if (isset($_POST["arrIds"])) {
    $arrIds = $_POST["arrIds"];
}

require "sql/sql_select.php";
$data = sql_select($table, $id, $lastId, $arrIds);

//update sql from file (only 1 poll)
if (null !== $id && isset($data[0])) {

    $row = $data[0];
    if (($row["v0"] + $row["v1"]) < 100 || time() - strtotime($row['t']) > 86400) { //24h
        require 'fileToSql.php';
        fileToSql($id, $table);

        //if updated
        $data = sql_select($table, $id);
    }
}

//get sql data:
try {
    echo json_encode($data);
} catch (Exception $e) {
    echo $data;
}