<?php

//this file way of download will reduce network usage, 
//and can split core and files environtments?

$url = $_POST["url"];

$userId = null;
if (isset($_POST["id"])) {
    $userId = $_POST["id"];
}


$content = file_get_contents($url);
//resolve utf
$json = mb_convert_encoding($content, 'UTF-8', mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));


$arr = json_decode($json . "]");

$options = array("calc" => "done");


for ($i = 3; $i < count($arr); $i++) {
    $votes = $arr[$i][1];

    if (is_array($votes)) {
        for ($j = 0; $j < count($votes); $j++) {
            $vote = $votes[$j];
            addVote($vote);
        }
    } else {
        addVote($votes);
    }

    if (isset($userId) && $userId == $arr[$i][0]) {
        $options["vt"] = $arr[$i][1];
    }
}

function addVote($vote) {
    if (empty($vote)) {
        return;
    }
    global $options;
    if (isset($options->$vote)) {
        $options[$vote] = $options[$vote] + 1;
    } else {
        $options[$vote] = 1;
    }
}

$newArr = array($arr[0], $arr[1], $arr[2], $options);

echo substr(json_encode($newArr), 0, -1);
