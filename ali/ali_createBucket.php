<?php

//should be loaded before:
require_once "ali/AliConnection.php";

function ali_createBucket($table) {
    $con = new AliConnection($table);
    $domain = $con->domain;
    $data = $con->data;
    $time = $con->time;

    $header = "x-oss-acl:public-read";

    //CURL GET META FILE:
    $data['Signature'] = $con->sign("PUT", $time, null, null, "$header\n");

    $ch_create = curl_init();
    curl_setopt($ch_create, CURLOPT_URL, "http://$domain/?" . http_build_query($data)); //preivate signature
    curl_setopt($ch_create, CURLOPT_RETURNTRANSFER, 1); //prevent auto-echo return
    curl_setopt($ch_create, CURLOPT_HEADER, 0);
    curl_setopt($ch_create, CURLOPT_CUSTOMREQUEST, "PUT");
    //PUBLIC READ HEADER:
    curl_setopt($ch_create, CURLOPT_HTTPHEADER, array($header));

    $res_create = curl_exec($ch_create);
    $curl_error = curl_error($ch_create);
    if ($curl_error) {
        echo "error in ali_createBucket(): $res_create";
    }
    curl_close($ch_create);

    return $curl_error;
}
