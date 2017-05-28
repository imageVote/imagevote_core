<?php

require_once "convBase.php";

function idKey($key) {
    $base = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $base10 = '0123456789';

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
