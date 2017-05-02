<?php

function sql_select($table, $id = null, $lastId = null) {
    if (empty($table)) {
        $table = "private";
    } else if (2 != strlen($table)) {
        echo "ERROR_TABLE $table";
        die();
    }

    include 'sql/connect.php'; //$connect, $user, $pass
    $pdo = new PDO($connect, $user, $pass);

    $q = "SELECT * FROM `$table`";
    if ($id) {
        $q .= " WHERE id = :id";
    }else if($lastId){
        $q .= " WHERE id > :lastId";
    }
    
    $sth = $pdo->prepare($q) or die(implode(":", $sth->errorInfo()) . " in $q");
    if ($id) {
        $sth->bindParam(":id", $id);
    }else if ($lastId) {
        $sth->bindParam(":lastId", $lastId);
    }

    $result = $sth->execute() or die(implode(":", $sth->errorInfo()) . " in $q");

    $arr = array();
    while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
        $arr[] = $row;
    }
    return $arr;
}
