<?php

require_once 'sql/connect.php'; //$connect, $user, $pass

function sql_create($data, $table = "private", $id = "NULL") {
    if (empty($table)) {
        $table = "private";
    }

    global $connect, $user, $pass;
    $pdo = new PDO($connect, $user, $pass);
    
    //IGNORE IF ALREADY EXISTS
    $q = "INSERT IGNORE INTO `$table` (id, data) VALUES (:id, :data)";
    $sth = $pdo->prepare($q);
    if (false === $sth) {
        if (mysql_errno() == 1062) {
            //error: duplicated key!
            die();
        }
        die(implode(":", $sth->errorInfo()) . " in $q");
    }

    $sth->bindParam(":data", $data);
    $sth->bindParam(":id", $id);
    $result = $sth->execute();

    //MANAGE ERRORS:
    if (false == $result) {
        sql_error($sth, $table, $q);
    }

    $id = $pdo->lastInsertId();

    if (empty($id)) {
        echo "ERROR_SQL_INSERT $q $data";
        die();
    }

    return $id;
}
