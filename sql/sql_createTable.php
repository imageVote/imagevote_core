<?php

require_once 'sql/connect.php'; //$connect, $user, $pass

function sql_createTable($lang, $error = "") {
    if (strlen($lang) != 2 && "private" !== $lang) {
        echo "wrong createTable length";
        die();
    }

    global $connect, $user, $pass;
    //echo "$connect, $user, $pass";
    try {
        $pdo = new PDO($connect, $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    } catch (Excpetion $e) {
        echo 'Connection failed: ' . $e->getMessage();
        die();
    }

    $cols = array(
        'id' => "bigint(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY",
        'data' => "varchar(255)",
        'v0' => "bigint(20) UNSIGNED DEFAULT 0",
        'v1' => "bigint(20) UNSIGNED DEFAULT 0",
        'v2' => "bigint(20) UNSIGNED DEFAULT 0",
        'likes' => "int(11) UNSIGNED DEFAULT 0",
        'reports' => "int(11) UNSIGNED DEFAULT 0",
        'err' => "tinyint(4) DEFAULT 0",
        't' => "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP"
    );

    //$q = "SELECT 1 FROM `$lang` LIMIT 1";
    $q = "SELECT count(*) as count FROM information_schema.TABLES WHERE TABLE_NAME = '$lang'";
    $sth = $pdo->prepare($q) or die(implode(":", $sth->errorInfo()) . " in $q");
    $sth->execute() or die(implode(":", $sth->errorInfo()) . " in $q");
    $row = $sth->fetch(PDO::FETCH_ASSOC);
    //echo json_encode($row);

    if ($row['count']) {
        //echo "error: $error; ";
        if ($error && strpos($error, "Unknown column '") > -1) {
            
            $shift = explode("' in 'field list'", $error)[0];
            $pop_arr = explode("Unknown column '", $shift);
            $col = array_pop($pop_arr);

            //echo "col: $col";
            $q = "ALTER TABLE `$lang` ADD $col {$cols[$col]}";
        } else {
            die("TODO: add all cols: $error");
            //TODO:
        }
    } else {
        $q = "CREATE TABLE $lang (";
        foreach ($cols as $key => $value) {
            $q .= "$key $value,";
        }
        $q = substr($q, 0, -1); //remova last ","
        $q .= ")";
    }

    $sth = $pdo->prepare($q) or die(implode(":", $sth->errorInfo()) . " in $q");
    $sth->execute() or die(implode(":", $sth->errorInfo()) . " in $q");
}
