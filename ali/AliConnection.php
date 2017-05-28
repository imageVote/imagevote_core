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
        if (empty($table)) {
            $table = "private";
        }
        $this->table = $table;
        $this->subdomain = "wouldyourather-$table";
        
        $this->domain = "{$this->subdomain}.oss-eu-central-1-internal.aliyuncs.com";
        require 'whitelist.php';
        if ($whitelisted) {
            $this->domain = "{$this->subdomain}.oss-eu-central-1.aliyuncs.com";
        }

        //data
        $this->time = time() + 20;
        $this->data = array(
            'Expires' => $this->time, //needed
            'OSSAccessKeyId' => $this->accessKeyId
        );
    }

    public function sign($VERB, $time, $path = "", $type = "") {
        $hash = $VERB . "\n\n"
                . $type . "\n"
                . $time . "\n"
                . "/{$this->subdomain}/$path";
        $this->hash = $hash;
        //echo $hash . "<br><br><br>";
        return base64_encode(hash_hmac('sha1', $hash, $this->accessKeySecret, true));
    }

}
