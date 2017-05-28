<?php

//should be loaded before:
require_once "ali/AliConnection.php";

function ali_createBucket($table) {
    $con = new AliConnection($table);
    $domain = $con->domain;
    $data = $con->data;
    $time = $con->time;

    //CURL GET META FILE:
    $data['Signature'] = $con->sign("PUT", $time);

    $ch_create = curl_init();    
    curl_setopt($ch_create, CURLOPT_URL, "http://$domain/?" . http_build_query($data)); //preivate signature
    curl_setopt($ch_create, CURLOPT_RETURNTRANSFER, 1); //prevent auto-echo return
    curl_setopt($ch_create, CURLOPT_HEADER, 0);
    curl_setopt($ch_create, CURLOPT_CUSTOMREQUEST, "PUT");
    $res_create = curl_exec($ch_create);
    if(curl_error($ch_create)){
        echo "error in ali_createBucket(): $res_create";
    }
    curl_close($ch_create);
    
    return $res_create;
}
