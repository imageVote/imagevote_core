<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$id = $_GET["id"];
$add = $_GET["add"];

$sub = null;
if (isset($_GET["sub"])) {
    $sub = $_GET["sub"];
}

switch ($add) {
    case 0:
        $prefix_add = "first";
        break;
    case 1:
        $prefix_add = "second";
        break;
}

switch ($sub) {
    case 0:
        $prefix_sub = "first";
        break;
    case 1:
        $prefix_sub = "second";
        break;
}

$post_values = '"' . $prefix_add . '_nvotes":{"__op":"Increment","amount":1}';
if (null != $sub) {
    $post_values .= ',"' . $prefix_sub . '_nvotes":{"__op":"Increment","amount":-1}';
}
$post = '{' . $post_values . '}';


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.parse.buddy.com/parse/classes/preguntas/" . $id);
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
curl_close($ch);

if (false == $reponse) {
    echo '_error in curl: ' . curl_error($ch);
    die();
}

echo $reponse;
