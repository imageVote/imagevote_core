<?php

function sql_create($data, $table = "private", $num_answers = 0) {
    if (empty($table)) {
        $table = "private";
    }

    require 'sql/connect.php'; //$connect, $user, $pass

    $pdo = new PDO($connect, $user, $pass);   
    $q = "INSERT INTO `$table` (data) VALUES (:data)";
    $sth = $pdo->prepare($q) or die(implode(":", $sth->errorInfo()) . " in $q");
    $sth->bindParam(":data", $data);
    $sth->execute() or die(implode(":", $sth->errorInfo()) . " in $q");
    $id = $pdo->lastInsertId();

    if (empty($id)) {
        echo "ERROR_SQL_INSERT $q $data";
        die();
    }

    return $id;
}
