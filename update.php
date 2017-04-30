<?php

//throw new Exception('Sorry, create polls is temporarily disabled. ', 'Sorry, create polls is temporarily disabled. ');
//throw new Exception('Your app version is old. Please update. ', 'Your app version is old. Please update. ');

include 'upload.php';

if ("update" == $action) {
    update($value);
//
} else if ("create" == $action) {
    create($value);
//
} else if ("newkey" == $action) {
//nothing else to do

} else {
    die("not action defined");
}

$key = "";

function create($value) {
    $arr = json_decode($value . "]");
    if (count($arr) > 4) { //q, opts, style, usrs
        echo "_wrong creation data";
        die();
    }

    global $visibility, $key;

    require 'sql/sql_create.php';
    $id = sql_create($value);
    echo "4";

    require "convBase.php";
    echo "5";
    global $base, $base10;
    $key = convBase($id, $base10, $base);
    echo "6 ($key) ";
    require 'ali/ali_append.php';
    echo "7";
    ali_append($key, $value, $visibility);
    echo "8";
}

function update($value) {
    global $public, $userId, $visibility, $key;
    $key = $_POST["key"];

    if ($public) {
        $arr = explode("|", $value);
        if ($arr[0] != $userId) {
            echo "_wrong user id: $arr[0] != $userId";
            die();
        }
    }

    require 'sql/sql_update.php';
    if (isset($_POST["add"]) && isset($_POST["remove"])) {
        $add = json_decode($_POST["add"]);
        $remove = json_decode($_POST["remove"]);
    }

    if (isset($_POST["add"]) && isset($_POST["remove"])) {
        require "convBase.php";
        global $base, $base10;
        $id = convBase($key, $base, $base10);
        sql_update("in", $id, $add, $remove);
    }

    require 'ali/ali_append.php';
    ali_append($key, PHP_EOL . $value, $visibility);
}

echo $key;
