<?php

$url = $_POST["url"];
if (!file_exists($url)) {
    die();
}

$db = new SQLite3($url);

$stmt = $db->prepare("SELECT file FROM find WHERE word IN (?) LIMIT 10");
$stmt->bindValue(1, $_POST["string"]);
$result = $stmt->execute();

while ($row = $result->fetchArray()) {
    echo $row['file'] . ",";
}


// FOR REGEXP SQL FINDS:
// http://stackoverflow.com/questions/3732246/mysql-in-with-like
