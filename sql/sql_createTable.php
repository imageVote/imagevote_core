<?php

require_once 'sql/connect.php'; //$connect, $user, $pass

function sql_createTable($lang) {
    if (strlen($lang) != 2) {
        echo "wrong createTable length";
        die();
    }
    
    global $connect, $user, $pass;
    $pdo = new PDO($connect, $user, $pass);

    $q = "CREATE TABLE $lang (
        id bigint(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        data varchar(255),
        v0 bigint(20) UNSIGNED DEFAULT 0,
        v1 bigint(20) UNSIGNED DEFAULT 0,
        v2 bigint(20) UNSIGNED DEFAULT 0,
        likes int(11) UNSIGNED DEFAULT 0,
        reports int(11) UNSIGNED DEFAULT 0,
        t TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    )";
    $sth = $pdo->prepare($q) or die(implode(":", $sth->errorInfo()) . " in $q");
    $sth->execute() or die(implode(":", $sth->errorInfo()) . " in $q");
}
