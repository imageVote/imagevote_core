<?php

function sql_select($table, $id) {
    if (empty($table)) {
        $table = "private";
    } else if (2 != strlen($table)) {
        echo "ERROR_TABLE $table";
        die();
    }

    include 'sql/connect.php'; //$connect, $user, $pass
    $pdo = new PDO($connect, $user, $pass);
    $q = "SELECT * FROM $table WHERE id = :id";
    $sth = $pdo->prepare($q) or die(implode(":", $sth->errorInfo()) . " in $q");
    $sth->bindParam(":id", $id);
    $result = $sth->execute() or die(implode(":", $sth->errorInfo()) . " in $q");

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row){
        return json_ecnode($row);
    }
}
