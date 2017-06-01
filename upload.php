<?php

//TODO: if some offshore uses his phone number to vote in other countries requesting with other country name


$base = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
$base10 = '0123456789';

//id here can be the public or the private
$userId = null;
if(isset($_POST["userId"])){
    $userId = $_POST["userId"];
}

//define public before newKey
$public = isset($_POST["public"]);


$visibility = "private";

//before final key -> public and url
if ($public) {
    $visibility = "public";

    //ISO is phoneCode countries
    $ISO = strtoupper($_POST["ISO"]);

    //check public key
    include_once('phone/hash.php');

    $doubleHash = $_POST["digitsKey"]; //captcha name to hks
    if (!checkDoubleHash($userId, $ISO, $doubleHash)) {
        echo "_1 Your country residence has changed? $userId, $ISO, $doubleHash"; //hash not works
        die();
    }

    if (isset($_POST["pollCountry"])) {
        //pollCountry can be org like 'EU'
        $pollCountry = strtolower($_POST["pollCountry"]);

        // needs === if pos is '0'
        $upperCountry = strtoupper($pollCountry);
        if (strpos($ISO, $upperCountry) === false) {

            //check in ORGS
            $json_data = file_get_contents('phone/orgs.json');
            $ORGS = json_decode($json_data, true);

            $isInORG = false;
            $ISOSarray = split(" ", $ISO);
            for ($i = 0; $i < count($ISOSarray); $i++) {
                if (array_search($ISO, $ORGS[$upperCountry]) !== NULL) {
                    $isInORG = true;
                }
            }

            if (false == $isInORG) {
                //bug error or hack
                echo "_not country coincidence '$ISO' is not in '$upperCountry' or '" . json_encode($ORGS[$upperCountry]) . "', please contact us";
                die();
            }
        }
    }
}
