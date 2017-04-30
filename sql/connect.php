<?php

$connect = "mysql:host=localhost;port=3306;dbname=wouldyourather";
$user = "root";
$pass = "&MOVy1PV";

require 'whitelist.php';
if ($whitelisted) {
    $connect = "mysql:host=47.91.75.28;port=3306;dbname=test_wouldyourather";
    $user = "test";
    $pass = "testing";
}
