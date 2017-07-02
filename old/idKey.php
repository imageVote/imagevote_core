<?php

$base = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
$base10 = '0123456789';
require_once "convBase.php";

function idKey($key) {
    global $base, $base10;

    $keyArr = preg_split('/(-|_)/', $key); //explode with '-' or '_'
    if (count($keyArr) > 1 && !empty($keyArr[0])) {
        $table = $keyArr[0];
    }

    if (!isset($table)) { //if private
        $key64 = substr($key, 1, -1);
    } else {
        $key64 = $keyArr[count($keyArr) - 1];
    }
    return convBase($key64, $base, $base10);
}

function keyId($id, $table) {
    global $base, $base10;

    $key = convBase($id, $base10, $base);
    if (empty($table) || "private" == $table) {
        $key = "-$key" . substr(rtrim(base64_encode($key), '='), -1);
    } else {
        $key = "{$table}_{$key}";
    }
    return $key;
}
