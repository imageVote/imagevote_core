<?php

$connect = "mysql:host=localhost;port=3306;dbname=wouldyourather";
$user = "root";
$pass = "&MOVy1PV";

$whitelist = array(
    '127.0.0.1',
    '::1'
);
$server_whitelist = array(
    '149.56.98.6'
);
if (in_array($_SERVER['REMOTE_ADDR'], $whitelist) || in_array($_SERVER['SERVER_ADDR'], $server_whitelist)) {
    $connect = "mysql:host=47.91.75.28;port=3306;dbname=test_wouldyourather";
    $user = "test";
    $pass = "testing";
}
