<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$ch = curl_init();
//$query = urlencode("{[objectId]:[BoKFYAgmOC]}");
//curl_setopt($ch, CURLOPT_URL, "https://api.parse.buddy.com/parse/classes/preguntas?where=$query&limit=20");
curl_setopt($ch, CURLOPT_URL, "https://api.parse.buddy.com/parse/classes/preguntas?limit=20");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'X-Parse-Application-Id: 4134380a-1991-4ad7-9f43-1e7f27855e29',
    'X-Parse-Client-Version: php1.2.1',
    'Expect: '
));

$prueba = curl_exec($ch);

if (false == $prueba) {
    echo 'Curl error: ' . curl_error($ch);
}

//echo $prueba;
var_dump($prueba);

curl_close($ch);


//require 'lib/parse-php-sdk/autoload.php';
//
//use Parse\ParseQuery;
//use Parse\ParseClient;
//use Parse\ParseException;
//
//$app_id = "4134380a-1991-4ad7-9f43-1e7f27855e29";
//$master_key = "6ZsfxZ6Zt5tD5ZNlWMenRfMB7FCJfLqb";
//ParseClient::initialize($app_id, null, $master_key);
//// Users of Parse Server will need to point ParseClient at their remote URL and Mount Point:
//ParseClient::setServerURL('https://api.parse.buddy.com', 'parse');
//
////
//$query = new ParseQuery("preguntas");
//try {
//    $gameScore = $query->get("BoKFYAgmOC");
//    echo $gameScore->getObjectId();
//    // The object was retrieved successfully.
//} catch (ParseException $ex) {
//    // The object was not retrieved successfully.
//    // error is a ParseException with an error code and message.
//    echo "catch error: ";
//    echo $ex->getMessage();
//}
