<?php

//set_time_limit(5);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$table = $_POST["table"];

if(false === strpos($table, "preguntas")){
    if("es" == $table){
        $table = "";
    }
    $table = "preguntas" . strtoupper($table);
}

$lastId = 0;
if (isset($_POST["lastId"])) {
    $lastId = $_POST["lastId"];
    $query = urlencode('{"approved":1,"idQ":{"$gte":' . $lastId . '}}') . "&order=idQ";
}

if (isset($_POST["id"])) {
    $id = $_POST["id"];
    $query = urlencode('{"idQ":' . $id . '}');
}

if (isset($_POST["objectId"])) {
    $objectId = $_POST["objectId"];
    $query = urlencode('{"objectId":"' . $objectId . '"}');
}

$arrIds = 0;
if (isset($_POST["arrIds"])) {
    $arrIds = $_POST["arrIds"];
    $query = urlencode('{"idQ":{"$in":[' . $arrIds . ']}}');
}


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.parse.buddy.com/parse/classes/$table?where=$query");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
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

//try {
//    $decode = json_decode($reponse);
//} catch (Exception $e) {
//    echo $reponse;
//    die();
//}
//
//if(!isset($decode->results)){
//    echo "_error: " + $reponse;
//    die();
//}
//
//$arr = $decode->results;

////echo "count: " . count($arr);
//$result = [];
//for ($i = 0; $i < count($arr); $i++) {
////    $question = $arr[$i];
//    array_push($result, [
//        $arr[$i]->objectId,
//        $arr[$i]->idQ,
//        $arr[$i]->first,
//        $arr[$i]->second,
//        $arr[$i]->first_nvotes,
//        $arr[$i]->second_nvotes
//    ]);
//}
//
//echo json_encode($result);
