<?php

require_once 'whitelist.php';

function urlStorage() {
    global $whitelisted;
    
    $domain = "oss-eu-central-1-internal.aliyuncs.com";
    if ($whitelisted) {
        $domain = "oss-eu-central-1.aliyuncs.com";
    }
    
    return $domain;
}
