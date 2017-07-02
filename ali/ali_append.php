<?php

require "ali/AliConnection.php";

function ali_append($id, $body, $table, $retry = false) {
    $con = new AliConnection($table);
    $domain = $con->domain;
    $data = $con->data;
    $time = $con->time;
    $file = "{$con->table}_{$id}";

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

    $url = "http://$domain/$path&" . http_build_query($data);
    curl_setopt($ch, CURLOPT_URL, $url);
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
                $curl_error = ali_createBucket($table);

                //if (1 == $res_create) {
                if ($curl_error) {
                    die("adding bucket error: $curl_error ($res)");
                }

                //RE-RUN:
                $res = curl_exec($ch);
                if ($res) {
                    die("resp: $res");
                }

                //CREATE MYSQL TABLE
                require 'sql/sql_createTable.php';
                sql_createTable($con->table);

                break;

            case "PositionNotEqualToLength":
                //could be on simultaneously update poll
                if (!$retry) {
                    //try again:
                    ali_append($id, $body, $table, true);
                }
                break;

            default:
                die("not xml code case for: $res");
        }
    }

    curl_close($ch);
    
    //actually not in use
    return $length;
}
