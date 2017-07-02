<?php

//$connect = "mysql:host=localhost;port=3306;dbname=wouldyourather";
//$user = "root";
//$pass = "&MOVy1PV";
//
//require 'whitelist.php';
//if ($whitelisted) {
//    $connect = "mysql:host=47.91.75.28;port=3306;dbname=test_wouldyourather";
//    $user = "test";
//    $pass = "testing";
//}
//

$_APPNAME = "wouldyourather";
include 'config.php'; //overrides $_APPNAME if exists

$connect = "mysql:host=47.91.75.28;port=3306;dbname=$_APPNAME";
$user = "externo";
$pass = "&MOVy1PV";

function sql_error($sth, $table, $query = "") {
    //echo "sql_error..";
    $error = implode(":", $sth->errorInfo()) . " (in sql_error)";
    switch ($sth->errorCode()) {
        case "42S02":
            require 'sql/sql_createTable.php';
            sql_createTable($table);
            $sth->execute();
            break;

        case "42S22":
            require 'sql/sql_createTable.php';
            sql_createTable($table, $error);
            $sth->execute();
            break;

        default:
            die("$error in: $query");
    }
}
