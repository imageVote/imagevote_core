<?php
echo "12345";
$connect = "mysql:host=localhost;port=3306;dbname=wouldyourather";
$user = "root";
$pass = "&MOVy1PV";

$whitelist = array(
    '127.0.0.1',
    '::1',
    '149.56.98.6'
);
if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
    $connect = "mysql:host=47.91.75.28;port=3306;dbname=test_wouldyourather";
    $user = "test";
    $pass = "testing";
}
echo "888";