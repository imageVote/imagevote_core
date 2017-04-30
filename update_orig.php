<?php

include 'upload.php';

if ("update" == $action) {
    update($value);
//
} else if ("create" == $action) {
    create($value);
//
} else if ("newkey" == $action) {
//nothing else to do
//
} else {
    die("not action defined");
}

echo $key;

function newKey() {
    global $public, $DATA_PATH;

    $path = $DATA_PATH . "private/";
    if ($public) {
        $path = $DATA_PATH . "public/";
    }
    $file = $path . "_last.txt";

    $handle = fopen($file, 'r+');
    if (false == $handle) {
        mkdir($path, 0777, true);

        $handle = fopen($file, 'w+');
        if (false == $handle) {
            file_put_contents("error.log", "$file file not exists; ", FILE_APPEND | LOCK_EX);
            echo "_error opening last key on $file; path: $path; \n";
            die();
        }
    }

    $content = fgets($handle);
    $new = getNextAlphaNumeric($content);
    fseek($handle, 0);
    fwrite($handle, $new);
    fclose($handle);

    if (!isset($content) || (empty($content) && "0" != $content)) { // "0" == empty()
        include_once 'error.php';
        error("illegible $path file; ");
        echo "_error on read last key on $path; ";
        die();
    }

    if ($public) {
        return $new;
    } else {
        global $base;
        return "-$new" . $base[rand(0, 61)]; //62-1
        //return "-$new";
    }
}

function create($value) {
    //for public - and private too
    $arr = json_decode($value . "]");
    if (count($arr) > 4) { //q, opts, style, usrs
        echo "_wrong creation data";
        return;
    }

    global $path, $key, $visibility;
    $url = $path . $key;
//    if (file_exists($url)) {
//        $key = newKey();
//        $url = $path . $key;
//    }
//    //file work only
//    $fp = fopen($url, "a");
//    if (false == $fp) {
//        global $path;
//        mkdir($path, 0777, true);
//        $fp = fopen($url, "a");
//        if (false == $fp) {
//            echo "_error: cant create file on create() on $url; path: $path; \n";
//            die();
//        }
//    }
//    $len = fwrite($fp, $value);
//    fclose($fp);
//    if (false == $len && !empty($value)) {
//        echo "_error: SERVER OUT OF SPACE! on $url; ";
//        die();
//    }

    require_once 'sql/sql_create.php';
    $id = sql_create("in", $value);
    require_once("convBase.php");
    global $base, $base10;
    $key = convBase($id, $base10, $base);
    require_once 'ali/ali_append.php';
    ali_append($key, $value, $visibility);

    global $public;
    if ($public) {
        global $path;

        $exists = true;
        if (!file_exists($path . '_index.txt')) {
            $exists = false;
            touch($path . "_index.txt");
        }

        $db = new SQLite3($path . '_index.txt');
        if (!$exists) {
            $db->exec('CREATE TABLE find (word varchar(255), file varchar (255))');
            //echo "Table people has been created \n";
        }
        $words = $arr[0];
        for ($i = 0; $i < count($words); $i++) {
            if (strlen($words[$i]) < 3) {
                continue;
            }
            $db->exec("INSERT INTO find (word, file) VALUES ({$words[$i]}, $key)");
        }
    }
}

function update($value) {
    //for public - and private too
    global $public, $userId, $path, $key, $visibility;
    $url = $path . $key;

//    $arr = json_decode($value);
//    if (!is_array($arr)) {
//        echo "_wrong update data: $value";
//        die();
//    } else 
    if ($public) {
        $arr = explode("|", $value);
        if ($arr[0] != $userId) {
            echo "_wrong user id: $arr[0] != $userId";
            die();
        }
    }

    //file work only
//    $fp = fopen($url, "a");
//    //http://stackoverflow.com/questions/18833448/php-flock-behaviour-when-file-is-locked-by-one-process
//    $count = 0;
//    $timeout_secs = 10; //number of seconds of timeout
//    $got_lock = true;
//    while (!flock($fp, LOCK_EX | LOCK_NB, $wouldblock)) {
//        $count++;
//        if ($wouldblock && $count < $timeout_secs) {
//            sleep(1);
//        } else {
//            $got_lock = false;
//            break;
//        }
//    }
//    if ($got_lock) {
//        // Do stuff with file
//        $len = fwrite($fp, PHP_EOL . $value);
//    } else {
//        echo "_error: cant save because file still locked";
//        die();
//    }
//    fclose($fp); //this release lock
//    //file work only
//    if (false == $fp) {
//        echo "_error: cant open file on update() on $url; (not www-data file?)";
//        die();
//    }
//    if (false == $len && !empty($value)) {
//        echo "_error: file not writable on update() on $url; "; //caution: is server is out of space?
//        die();
//    }

    require_once 'sql/sql_update.php';
    $add = json_decode($_POST["add"]);
    $remove = json_decode($_POST["remove"]);
    require_once("convBase.php");
    global $base, $base10;
    $id = convBase($key, $base, $base10);
    sql_update("in", $id, $add, $remove);
    require_once 'ali/ali_append.php';
    ali_append($key, PHP_EOL . $value, $visibility);
}

//for key sort
function getNextAlphaNumeric($code) {
    global $base, $base10;
    //tested Mayus correct order on linux biz envirentment
    
    include_once("convBase.php");
    $base_ten = convBase($code, $base, $base10);
    return convBase($base_ten + 1, $base10, $base);
}
