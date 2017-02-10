<?php

echo "ola;";
//curl -X GET \
//-H "X-Parse-Application-Id: ${APPLICATION_ID}" \
//-H "X-Parse-REST-API-Key: ${REST_API_KEY}" \
//https://api.parse.com/1/classes/GameScore/Ed1nuqPvcm

$ch = curl_init();

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_URL, "https://api.parse.buddy.com/parse/1/classes/preguntas");

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'X-Parse-Application-Id: 4134380a-1991-4ad7-9f43-1e7f27855e29'
));

$prueba = curl_exec($ch);

if (false == $prueba) {
    echo 'Curl error: ' . curl_error($ch);
}

//echo $prueba;
var_dump($prueba);

curl_close($ch);

