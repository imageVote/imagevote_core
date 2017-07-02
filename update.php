<?php

//throw new Exception('Sorry, create polls is temporarily disabled. ', 'Sorry, create polls is temporarily disabled. ');
//throw new Exception('Your app version is old. Please update. ', 'Your app version is old. Please update. ');

if (isset($_POST["action"])) {
    $action = $_POST["action"];
}
        
ob_start();

if ("update" == $action) {
    update();
//
} else if ("create" == $action) {
    create();
//
} else if ("newkey" == $action) {
    //

} else {
    die("not action defined");
}

function create() {
    include 'create.php';
}

function update() {
    include 'add.php';
}

//old android
$block = ob_get_contents();
ob_end_clean();

if(empty($block)){
    echo 0;
}else{
    echo $block;
}

