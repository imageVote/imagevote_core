<?php

//TODO: if some offshore uses his phone number to vote in other countries requesting with other country name
include_once 'settings.php';

//create save path if not exists
if (empty($DATA_PATH) || !file_exists($DATA_PATH)) {
    $DATA_PATH = "data/";
    if (!file_exists($DATA_PATH)) {
        mkdir($DATA_PATH, 0777, true);
    }
}

$base = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

//id here can be the public or the private
$userId = $_POST["id"];

if (!isset($_POST["action"])) {
    echo "_error on index.* call: not action";
    die();
}
$action = $_POST["action"];

$key = "";
if (isset($_POST["key"])) {
    $key = $_POST["key"];
}

if (isset($_POST["value"])) {
    $value = $_POST["value"];
}

//define public before newKey
$public = isset($_POST["public"]);

if (empty($key)) {
    $key = newKey();
}

$path = $DATA_PATH;
//before final key -> public and url
if ($public) {
    //ISO is phoneCode countries
    $ISO = strtoupper($_POST["ISO"]);

    //check public key
    include_once('hash.php');

    $doubleHash = $_POST["digitsKey"]; //captcha name to hks
    if (!checkDoubleHash($userId, $ISO, $doubleHash)) {
        echo "_1 Your country residence has changed? $userId, $ISO, $doubleHash"; //hash not works
        die();
    }

    $DATA_PATH .= "public/";

    if (isset($_POST["pollCountry"])) {
        //pollCountry can be org like 'EU'
        $pollCountry = strtolower($_POST["pollCountry"]);

        // needs === if pos is '0'
        $upperCountry = strtoupper($pollCountry);
        if (strpos($ISO, $upperCountry) === false) {

            //check in ORGS
            $json_data = file_get_contents('orgs.json');
            $ORGS = json_decode($json_data, true);

            $isInORG = false;
            $ISOSarray = split(" ", $ISO);
            for ($i = 0; $i < count($ISOSarray); $i++) {
                if (array_search($ISO, $ORGS[$upperCountry]) !== NULL) {
                    $isInORG = true;
                }
            }

            if (false == $isInORG) {
                //bug error or hack
                echo "_not country coincidence '" . $ISO . "' is not in '" . $upperCountry . "' or '" . json_encode($ORGS[$upperCountry]) . "', please contact us";
                die();
            }
        }

        $path .= "~$pollCountry/";
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }
    $url = $path . $key;

    //
} else {
    $path .= "private/";
    $url = $path . $key;
}

if ("update" == $action) {
    update($url, $value);
//
} else if ("create" == $action) {
    create($key, $value);
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

    if ($public) {
        $path = $DATA_PATH . "public/";
    } else {
        $path = $DATA_PATH . "private/";
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

function create($key, $value) {
    //for public - and private too
    $arr = json_decode($value . "]");
    if (count($arr) > 4) { //q, opts, style, usrs
        echo "_wrong creation data";
        return;
    }

    global $url;
    if (file_exists($url)) {
        echo "_3 file already exists";
        return;
    }

    //file work only
    $fp = fopen($url, "a");

    if (false == $fp) {
        global $path;
        mkdir($path, 0777, true);

        $fp = fopen($url, "a");
        if (false == $fp) {
            echo "_error: cant create file on create() on $url; path: $path; \n";
            die();
        }
    }

    $len = fwrite($fp, $value);
    fclose($fp);

    if (false == $len && !empty($value)) {
        echo "_error: SERVER OUT OF SPACE! on $url; ";
        die();
    }

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

function update($url, $value) {
    //for public - and private too
    $arr = json_decode($value);

    global $userId;
    if (!is_array($arr)) {
        echo "_wrong update data: $value";
        die();
    } else if ($arr[0] != $userId) {
        echo "_wrong user id: $arr[0] != $userId";
        die();
    }

    //file work only
    $fp = fopen($url, "a");

    //http://stackoverflow.com/questions/18833448/php-flock-behaviour-when-file-is-locked-by-one-process
    $count = 0;
    $timeout_secs = 10; //number of seconds of timeout
    $got_lock = true;
    while (!flock($fp, LOCK_EX | LOCK_NB, $wouldblock)) {
        $count++;
        if ($wouldblock && $count < $timeout_secs) {
            sleep(1);
        } else {
            $got_lock = false;
            break;
        }
    }
    if ($got_lock) {
        // Do stuff with file
        $len = fwrite($fp, ",$value");
    } else {
        echo "_error: cant save because file still locked";
        die();
    }

    fclose($fp); //this release lock
    //file work only

    if (false == $fp) {
        echo "_error: cant open file on update() on $url; (not www-data file?)";
        die();
    }
    if (false == $len && !empty($value)) {
        echo "_error: file not writable on update() on $url; "; //caution: is server is out of space?
        die();
    }
}

//for key sort
function getNextAlphaNumeric($code) {
    global $base;
    //tested Mayus correct order on linux biz envirentment
    $base10 = '0123456789';

    include_once("convBase.php");
    $base_ten = convBase($code, $base, $base10);
    return convBase($base_ten + 1, $base10, $base);
}
