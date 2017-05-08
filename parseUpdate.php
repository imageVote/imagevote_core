<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$table = $_POST["table"];
$id = $_POST["id"];
$add = json_decode($_POST["add"]);

$sub = null;
if (isset($_POST["sub"])) {
    $sub = json_decode($_POST["sub"]);
}


$pos = ["first", "second"];

//$post_values = '';
//force get all votes values
$post_values = '"first_nvotes":{"__op":"Increment","amount":0}, "second_nvotes":{"__op":"Increment","amount":0},';

for ($i = 0; $i < count($add); $i++) {
    $post_values .= '"' . $pos[$add[$i]] . '_nvotes":{"__op":"Increment","amount":1}';
    if (count($add) > $i + 1) {
        $post_values .= ",";
    }
}

if (null != $sub) {
    $post_values .= ",";
    for ($i = 0; $i < count($sub); $i++) {
        $post_values .= '"' . $pos[$sub[$i]] . '_nvotes":{"__op":"Increment","amount":-1}';
        if (count($sub) > $i + 1) {
            $post_values .= ",";
        }
    }
}

$post = '{' . $post_values . '}';


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.parse.buddy.com/parse/classes/$table/$id");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($post),
    'X-Parse-Application-Id: 4134380a-1991-4ad7-9f43-1e7f27855e29',
    'X-Parse-Client-Version: php1.2.1',
    'Expect: '
));

//LOCALHOST TEST SSL:
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

$reponse = curl_exec($ch);

if (false == $reponse) {
    echo '_error in curl: ' . curl_error($ch);
    die();
}
curl_close($ch);

$obj = json_decode($reponse);
$obj->idQ = $_POST["idQ"];

echo json_encode($obj);
