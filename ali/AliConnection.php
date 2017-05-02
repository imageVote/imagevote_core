<?php

class AliConnection {

    private $accessKeyId = "LTAIk2eFMXXUNg09";
    private $subdomain;
    //
    public $accessKeySecret = 'hEVKsi8OS9bpA7miFaiznIjTjHnoGv';
    public $domain;
    public $data;
    public $time;
    //debug:
    public $hash;

    public function __construct($table = "private") {
//        $this->subdomain = "wouldyourather-$table";
//        $this->domain = "{$this->subdomain}.oss-eu-central-1-internal.aliyuncs.com";
//
//        require 'whitelist.php';
//        if ($whitelisted) {
//            $this->subdomain = "wouldyourather-$table-test";
//            $this->domain = "{$this->subdomain}.oss-eu-central-1.aliyuncs.com";
//        }
        
        //override migration
        $this->subdomain = "wouldyourather-$table";
        $this->domain = "{$this->subdomain}.oss-eu-central-1.aliyuncs.com";

        //data
        $this->time = time() + 20;
        $this->data = array(
            'Expires' => $this->time, //needed
            'OSSAccessKeyId' => $this->accessKeyId
        );
    }

    public function sign($VERB, $time, $path, $type = "") {
        $hash = $VERB . "\n\n"
                . $type . "\n"
                . $time . "\n"
                . "/{$this->subdomain}/$path";
        $this->hash = $hash;
        return base64_encode(hash_hmac('sha1', $hash, $this->accessKeySecret, true));
    }

}
