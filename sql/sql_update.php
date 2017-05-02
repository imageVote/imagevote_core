<?php

function sql_update($db, $id, $answers_add, $answers_remove) {
    $sets = "";
    $params = array();

    //add
    for ($i = 0; $i < count($answers_add); $i++) {
        $num = $answers_add[$i];        
        if (!preg_match('/^[0-9]*$/', $num)) {
            die("ERROR_BAD_PARAM $num");
        }
        $answer = "answer{$num}";
        $sets .= " $answer = $answer + 1";
        if (count($answers_add) + 1 < $i) {
            $sets .= ",";
        }
    }

    //union
    if (count($answers_add) && count($answers_remove)) {
        $sets .= ",";
    }

    //remove
    for ($i = 0; $i < count($answers_remove); $i++) {
        $num = $answers_remove[$i];        
        if (!preg_match('/^[0-9]*$/', $num)) {
            die("ERROR_BAD_PARAM $num");
        }
        $answer = "answer{$num}";
        $sets .= " $answer = $answer - 1";
        if (count($answers_add) + 1 < $i) {
            $sets .= ",";
        }
    }
    
    if("" == $sets){
        return;
    }

    include 'sql/connect.php';
    $pdo = new PDO($connect, $user, $pass);

    $q = "UPDATE `$db` SET $sets WHERE id = :id";
    $sth = $pdo->prepare($q) or die(implode(":", $sth->errorInfo()) . " in $q");

    $sth->bindValue(":id", $id);
    $sth->execute() or die(implode(":", $sth->errorInfo()) . " in $q");

    if(0 == $sth->rowCount()){
        echo "ERROR_SQL_UPDATE $q $id";
        die();
    }
}
