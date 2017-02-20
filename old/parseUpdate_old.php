<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$update = $_POST["update"];
$parse = json_decode($update);

$str = '{
        "requests": [';
for ($i = 0; $i < count($parse); $i++) {
//    $poll = $parse[$i];
    $str .= '{
            "method": "PUT",
            "path": "/1/classes/preguntas/' . $parse[$i]->id . '",
            "body": {
              "first_nvotes": ' . $parse[$i]->first . ',
              "second_nvotes": ' . $parse[$i]->second . ' 
            }
          },';
    if ($i + 1 < count($parse)) {
        $str .= ',';
    }
}
$str .= ']}';

echo $str;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.parse.buddy.com/parse/batch");
curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
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

if (false == $reponse) {
    echo '_error in curl: ' . curl_error($ch);
}

curl_close($ch);

echo "\n" . json_encode($reponse);
