<?php

require_once "AliConnection.php";

//$file = "file4.txt"; //$body = "0";
function ali_select($visibility, $file) {

    $con = new AliConnection($visibility);
    $accessKeySecret = $con->accessKeySecret;
    $domain = $con->domain;
    $data = $con->data;
    $time = $con->time;

    //CURL APPEND:
    $path = "$file?";
    $data['Signature'] = $con->sign("POST", $time, $path, "application/x-www-form-urlencoded");

    curl_setopt($ch, CURLOPT_URL, "http://$domain/$path&" . http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //prevent auto-echo return
    curl_setopt($ch, CURLOPT_HEADER, 0);

    $res = curl_exec($ch);
    curl_close($ch);
    
    return $res;
}

ali_select("");
