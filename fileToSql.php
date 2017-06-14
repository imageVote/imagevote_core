<?php

require_once 'sql/connect.php';
require_once 'urlStorage.php';

function fileToSql($id, $table) {

    $tableString = $table;
    if (!$table) {
        $tableString = "private";
    }

    $url = urlStorage();
    $path = "http://wouldyourather-$tableString.$url/$id?nocache=" . rand();

    //("file_exists()" not work from localhost!)
    //ONLY CAN SELECT BY FOPEN BECAUSE PUBLIC (configure ip CORS)
    $fp = @fopen($path, 'r');
    if (!$fp) {
        //IF NOT EXISTS ANY VOTE FILE, DO NOTHING (NO VOTED POLL)
        //echo "!file_exists($path)";
        return;
    }

    $answers = array();
    while ($line = fgets($fp)) {
        if ('' == trim($line)) {
            continue;
        }
        $arr = explode("|", $line);
        $votes = json_decode($arr[1]);
        for ($i = 0; $i < count($votes); $i++) {
            $answers[$arr[0]] = (int) $votes[$i];
        }
    }

    $res = array(0, 0);
    foreach ($answers as $userId => $vote) {
        if (!isset($res[$vote])) {
            require_once 'sql/sql_addError.php';
            sql_addError($id, $table);
            echo "error reading '" . file_get_contents($path) . "' in $path";
            die();
        }
        $res[$vote] += 1;
    }


    $set = "";
    for ($i = 0; $i < count($res); $i++) {
        $set .= " v$i = $res[$i]";
        if (count($res) > $i + 1) {
            $set .= ",";
        }
    }

    //update    
    global $connect, $user, $pass;
    $pdo = new PDO($connect, $user, $pass);

    $q = "UPDATE `$tableString` SET $set WHERE id = :id";
    $sth = $pdo->prepare($q) or die(implode(":", $sth->errorInfo()) . " in $q");
    $sth->bindParam(":id", $id);
    $sth->execute() or die(implode(":", $sth->errorInfo()) . " in $q");
}
