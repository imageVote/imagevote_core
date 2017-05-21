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
$connect = "mysql:host=47.91.75.28;port=3306;dbname=wouldyourather";
$user = "externo";
$pass = "&MOVy1PV";

function sql_error($sth, $table) {
    switch ($sth->errorCode()) {
        case "42S02":
            require 'sql/sql_createTable.php';
            sql_createTable($table);
            $sth->execute();
            break;

        default:
            echo $sth->errorInfp() . " (in sql_error)";
            die();
    }
}
