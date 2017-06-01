<?php

require_once 'sql/connect.php'; //$connect, $user, $pass

function sql_addError($id, $db = "private", $error = 1) {
    if(empty($db)){
        $db = "private";
    }
    global $connect, $user, $pass;
    $pdo = new PDO($connect, $user, $pass);

    $q = "UPDATE `$db` SET err = :error WHERE id = :id";
    $sth = $pdo->prepare($q) or die(implode(":", $sth->errorInfo()) . " in $q");

    $sth->bindValue(":id", $id);
    $sth->bindValue(":error", $error);
    $result = $sth->execute();

    //MANAGE ERRORS:
    if (false == $result) {
        sql_error($sth, $db, $q);
    }
}
