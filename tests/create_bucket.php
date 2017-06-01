<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
//
////should be loaded before:
//require_once "ali/AliConnection.php";
//
//
//$con = new AliConnection("bb");
//$domain = $con->domain;
//$data = $con->data;
//$time = $con->time;
//
//$header = "x-oss-acl:public-read";
//
////CURL GET META FILE:
//$data['Signature'] = $con->sign("PUT", $time, null, null, "$header\n");
//
//$ch_create = curl_init();
//$url = "http://$domain/?" . http_build_query($data);
//curl_setopt($ch_create, CURLOPT_URL, $url); //preivate signature
//curl_setopt($ch_create, CURLOPT_RETURNTRANSFER, 1); //prevent auto-echo return
//curl_setopt($ch_create, CURLOPT_HEADER, 0);
//curl_setopt($ch_create, CURLOPT_CUSTOMREQUEST, "PUT");
//
////PUBLIC READ HEADER:
//curl_setopt($ch_create, CURLOPT_HTTPHEADER, array($header));
//
//$res_create = curl_exec($ch_create);
//echo $res_create;
//
//if (curl_error($ch_create)) {
//    echo "error in ali_createBucket(): $res_create";
//}
