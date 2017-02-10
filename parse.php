<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "ola;";

//curl -X GET \
//-H "X-Parse-Application-Id: ${APPLICATION_ID}" \
//-H "X-Parse-REST-API-Key: ${REST_API_KEY}" \
//https://api.parse.com/1/classes/GameScore/Ed1nuqPvcm
//$ch = curl_init();
//
//curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_URL, "https://api.parse.buddy.com/parse/1/classes/preguntas");
//
//curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//    'X-Parse-Application-Id: 4134380a-1991-4ad7-9f43-1e7f27855e29'
//));
//
//$prueba = curl_exec($ch);
//
//if (false == $prueba) {
//    echo 'Curl error: ' . curl_error($ch);
//}
//
////echo $prueba;
//var_dump($prueba);
//
//curl_close($ch);

require 'lib/parse-php-sdk/autoload.php';

use Parse\ParseQuery;
use Parse\ParseClient;
use Parse\ParseException;

$app_id = "4134380a-1991-4ad7-9f43-1e7f27855e29";
$master_key = "6ZsfxZ6Zt5tD5ZNlWMenRfMB7FCJfLqb";
ParseClient::initialize($app_id, null, $master_key);
// Users of Parse Server will need to point ParseClient at their remote URL and Mount Point:
ParseClient::setServerURL('https://api.parse.buddy.com', 'parse');

//
$query = new ParseQuery("TestObject");
$object = $query->get("BoKFYAgmOC");

$query->limit(10); // default 100, max 1000

$results = $query->find();

// Process ALL (without limit) results with "each".
// Will throw if sort, skip, or limit is used.
$query->each(function($obj) {
    echo $obj->getObjectId();
});

echo "\n fin";
