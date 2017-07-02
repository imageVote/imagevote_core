<?php

require_once 'urlStorage.php';

class AliConnection {

    private $accessKeyId = "LTAIk2eFMXXUNg09";
    //
    public $accessKeySecret = 'hEVKsi8OS9bpA7miFaiznIjTjHnoGv';
    public $domain;
    public $subdomain;
    public $table;
    public $data;
    public $time;
    //debug:
    public $hash;

    public function __construct($table = "private") {
        include 'config.php'; //$_APPNAME

        if (empty($table)) {
            $table = "private";
        }
        $this->table = $table;
        $this->subdomain = "{$_APPNAME}-{$table}";

//        $this->domain = "{$this->subdomain}.oss-eu-central-1-internal.aliyuncs.com";
//        require 'whitelist.php';
//        if ($whitelisted) {
//            $this->domain = "{$this->subdomain}.oss-eu-central-1.aliyuncs.com";
//        }
        $url = urlStorage();
        $this->domain = "{$this->subdomain}.$url";

        //data
        $this->time = time() + 20;
        $this->data = array(
            'Expires' => $this->time, //needed
            'OSSAccessKeyId' => $this->accessKeyId
        );
    }

    public function sign($VERB, $time, $path = "", $type = "", $headers = "") {
        $hash = $VERB . "\n\n"
                . $type . "\n"
                . $time . "\n"
                . $headers
                . "/{$this->subdomain}/$path";
        $this->hash = $hash;
        //echo $hash . "<br><br><br>";
        return base64_encode(hash_hmac('sha1', $hash, $this->accessKeySecret, true));
    }

}
