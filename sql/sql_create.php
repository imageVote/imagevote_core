<?php

require 'sql/connect.php'; //$connect, $user, $pass

function sql_create($data, $table = "private", $id = "NULL") {
    if (empty($table)) {
        $table = "private";
    }
    
    global $connect, $user, $pass;
    $pdo = new PDO($connect, $user, $pass);
    
    $q = "INSERT INTO `$table` (id, data) VALUES (:id, :data)";
    $sth = $pdo->prepare($q) or die(implode(":", $sth->errorInfo()) . " in $q");
    $sth->bindParam(":data", $data);
    $sth->bindParam(":id", $id);
    $result = $sth->execute();

    //MANAGE ERRORS:
    if (false == $result) {
        sql_error($sth, $table);
    }

    $id = $pdo->lastInsertId();

    if (empty($id)) {
        echo "ERROR_SQL_INSERT $q $data";
        die();
    }

    return $id;
}
