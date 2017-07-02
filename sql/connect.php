<?php

include 'config.php'; //$_APPNAME //$connect //$user //$pass
if (!isset($connect) || !isset($user) || !isset($pass)) {
    die("ERROR MISSING CONNECTION CREDENTIALS");
}

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
