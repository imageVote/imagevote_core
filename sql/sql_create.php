<?php

function sql_create($table, $data, $num_answers = 0) {
    if (empty($table)) {
        $table = "private";
    }
    echo "a";
    include 'connect.php'; //$connect, $user, $pass
    $pdo = new PDO($connect, $user, $pass);
echo "b";
    $q = "INSERT INTO `$table` (data) VALUES (:data)";
    $sth = $pdo->prepare($q) or die(implode(":", $sth->errorInfo()) . " in $q");
    $sth->bindParam(":data", $data);
    $sth->execute() or die(implode(":", $sth->errorInfo()) . " in $q");
    $id = $pdo->lastInsertId();
echo "c";
    if (empty($id)) {
        echo "ERROR_SQL_INSERT $q $data";
        die();
    }
    echo "d";
    return $id;
}
