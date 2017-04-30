<?php

require_once "ali/AliConnection.php";

//$file = "file4.txt"; //$body = "0";
function ali_append($file, $body, $visibility) {
    
    $con = new AliConnection($visibility);    
    $accessKeySecret = $con->accessKeySecret;
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
            break;
        }
    }

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
    //on error:
    if($res){
        echo "file length: $length <br><br>";
        echo "$con->hash <br><br>";
        echo $res;
        die();
    }    
    
    curl_close($ch);
}
