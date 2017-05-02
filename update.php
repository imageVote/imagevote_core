<?php

//throw new Exception('Sorry, create polls is temporarily disabled. ', 'Sorry, create polls is temporarily disabled. ');
//throw new Exception('Your app version is old. Please update. ', 'Your app version is old. Please update. ');

if (isset($_POST["action"])) {
    $action = $_POST["action"];
}
$key = "0"; //old android
        
        
if ("update" == $action) {
    update();
//
} else if ("create" == $action) {
    create();
//
} else if ("newkey" == $action) {
    newKey();

} else {
    die("not action defined");
}

function create() {
    include 'create.php';
}

function update() {
    include 'add.php';
}

function newKey(){
    
}

echo $key;
