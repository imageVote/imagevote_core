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

    public function __construct($visibility) {
        $this->subdomain = "wouldyourather-$visibility";
        $this->domain = "{$this->subdomain}.oss-eu-central-1-internal.aliyuncs.com";

        //local tests:
        $whitelist = array(
            '127.0.0.1',
            '::1',
            '149.56.98.6'
        );
        if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
            $this->subdomain = "wouldyourather-$visibility-test";
            $this->domain = "{$this->subdomain}.oss-eu-central-1.aliyuncs.com";
        }

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
