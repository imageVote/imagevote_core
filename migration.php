<?php

require_once 'sql/connect.php'; //$connect, $user, $pass
$pdo = new PDO($connect, $user, $pass);

require_once 'sql/sql_create.php';
require_once 'ali/ali_append.php';

$languages = ["en", "es", "it", "pt", "fr", "de"];

for($i = 0; $i < count($languages); $i++){
    $lang = $languages[$i];
    $arr = parseSelectAll($lang);

    $q = "SELECT * FROM `$lang`";
    $sth = $pdo->prepare($q) or die(implode(":", $sth->errorInfo()) . " in $q");    
    $sth->execute() or die(implode(":", $sth->errorInfo()) . " in $q");
    $existArr = array();
    while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
        $existArr[$row['id']] = $row;
    }    
    
    for($j = 0; $j < count($arr); $j++){
        $q = $arr[$j];
        $exist = $existArr[$q['idQ']];
        if($exist){
            if($exist["data"].indexOf($q[''])){
                
            }
        }
        sql_create();
    }
}

///////////////////////////////////////////////////////////////////////////////

//same as sql_sort but all data (idQ,first_nvotes,second_nvotes,badgrammar,reported)
function parseSelectAll($table) {
    if ("es" == $table) {
        $table = "";
    }
    $table = "preguntas" . strtoupper($table);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-Parse-Application-Id: 4134380a-1991-4ad7-9f43-1e7f27855e29',
        'X-Parse-Client-Version: php1.2.1',
        'Expect: '
    ));
    //LOCALHOST TESTS SSL:
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $all = array();
    $skip = 0;
    $next = true;

    while ($next) {
        $query = urlencode('{"approved":1}');
        $extra = "limit=1000&skip=$skip";
        curl_setopt($ch, CURLOPT_URL, "https://api.parse.buddy.com/parse/classes/$table?where=$query&$extra");
        $reponse = curl_exec($ch);

        if (false == $reponse) {
            echo '_error in curl: ' . curl_error($ch);
            die();
        }

        $data = json_decode($reponse);
        if (!isset($data->results)) {
            echo $reponse;
            die();
        }

        $arr = $data->results;

        if (count($arr) < 1000) {
            $next = false;
        }

        $all = array_merge($all, $arr);
        $skip += 1000;
    }

    curl_close($ch);

    return $all;
}
