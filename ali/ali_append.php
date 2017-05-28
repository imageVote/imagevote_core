<?php

require "ali/AliConnection.php";

//$file = "file4.txt"; //$body = "0";
function ali_append($file, $body, $table) {
    $con = new AliConnection($table);
    $domain = $con->domain;
    $data = $con->data;
    $time = $con->time;

    //CURL GET META FILE:
    $path = "$file?objectMeta";
    $data['Signature'] = $con->sign("HEAD", $time, $path);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://$domain/$path&" . http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1); // length will be at output header
    curl_setopt($ch, CURLOPT_NOBODY, 1); //prevent stuck
    $res = curl_exec($ch);

    //get length
    $length = 0;
    $headers = explode("\n", $res);
    foreach ($headers as $header) {
        if (stripos($header, 'x-oss-next-append-position:') !== false) {
            $length = trim(explode(":", $header)[1]);
            if ("" === $length) {
                die('!isset($length)');
            }
            break;
        }
    }

    ////////////////////////////////////////////////////////////////////////////
    //CURL APPEND:
    $path = "$file?append&position=$length";
    $data['Signature'] = $con->sign("POST", $time, $path, "application/x-www-form-urlencoded");

    curl_setopt($ch, CURLOPT_URL, "http://$domain/$path&" . http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //prevent auto-echo return
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    //data
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Length: " . strlen($body)
    ));

    $res = curl_exec($ch);

    //on error (like not exists this language bucket):
    if ($res) {
        $xml = simplexml_load_string($res) or die("Error: Cannot create object");
        switch ($xml->Code) {            
            case "NoSuchBucket":
                //CREATE BUCKET
                require 'ali/ali_createBucket.php';
                $res_create = ali_createBucket($table);

                //if (1 == $res_create) {
                if (curl_error($res_create)) {
                    die("adding bucket error: $res_create ($res)");
                }

                //RE-RUN:
                $res = curl_exec($ch);
                if ($res) {
                    die("res: $res");
                }

                //CREATE MYSQL TABLE
                require 'sql/sql_createTable.php';
                sql_createTable($con->table);

                break;

            default:
                die("not xml code case for: $res");
        }
    }

    curl_close($ch);

    return $length;
}
