<?php

$table = $_POST["table"];
$dir = "sort";

if (file_exists("$dir/$table-1.txt") && time() - filemtime("$dir/$table-1.txt") < 360000) { // < 20h
    //wich
    $file_num = 1;
    if (isset($_POST["file"])) {
        $file_num = $_POST["file"];
    }

    echo file_get_contents("$dir/$table-$file_num.txt");
    die();
}

$parse = array("preguntas", "preguntasEN", "preguntasIT", "preguntasFR", "preguntasDE", "preguntasPT");

$all = array(); //same array type

if (in_array($table, $parse)) {
    $parse = parseSelectAll($table);
    for ($i = 0; $i < count($parse); $i++) {
        $row = $parse[$i];
        $badgrammar = 0;
        if (isset($row->badgrammar)) {
            $badgrammar = $row->badgrammar;
        }
        $reported = 0;
        if (isset($row->reported)) {
            $reported = $row->reported;
        }

        $arr = array();
        $arr['id'] = $row->idQ;
        $arr['v0'] = !empty($row->first_nvotes) ? $row->first_nvotes : 0;
        $arr['v1'] = !empty($row->second_nvotes) ? $row->second_nvotes : 0;
        $arr['reports'] = $badgrammar + $reported;
        $arr['score'] = (($arr['v0'] + $arr['v1']) / 10) - $arr['reports'];
        $all[] = $arr;
    }
    //
} else {
    //ON OWN SERVER:
    if (empty($table)) {
        $table = "private";
    } else if (2 != strlen($table)) {
        echo "ERROR_TABLE $table";
        die();
    }

    require_once 'sql/connect.php'; //$connect, $user, $pass
    $pdo = new PDO($connect, $user, $pass);
    $q = "SELECT id, v0, v1, reports FROM `$table`";
    $sth = $pdo->prepare($q);
    if (false == $sth) {
        echo $sth->errno . "; ";
        die();
    }
    $result = $sth->execute();

    //MANAGE ERRORS:
    if (false == $result) {
        sql_error($sth, $table);
    }

    while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
        $row['score'] = (($row['v0'] + $row['v1']) / 10) - $row['reports'];
        $all[] = $row;
    }
}

usort($all, function($a, $b) {
    return ($b['score'] < $a['score']) ? -1 : 1;
});

//delete
$mask = "$dir/$table-*.txt";
array_map('unlink', glob($mask));

//write
$num = 1;
$handle = fopen("$dir/$table-$num.txt", "w");
for ($i = 0; $i < count($all); $i++) {
    $data = $all[$i]["id"] . ",";
    fwrite($handle, $data);
    if ($i < 100) {
        echo $data;
    }

    //next file
    if ($i && 0 === $i % 100) {
        $num++;
        fclose($handle);
        $handle = fopen("$dir/$table-$num.txt", "w");
    }
}
fclose($handle);

///////////////////////////////////////////////////////////////////////////////

function parseSelectAll($table) {
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
        $extra = "limit=1000&skip=$skip&keys=idQ,first_nvotes,second_nvotes,badgrammar,reported";
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
