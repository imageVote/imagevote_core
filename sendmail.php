<?php

$from = "would-you-rather";
$title = $_POST['title'];
$msg = $_POST['msg'];

exec('echo -e "From: ' . $from . ' \nTo: would \nSubject:' . $title . ' \n' . $msg . '"  | curl smtps://smtp.gmail.com:465 --mail-rcpt "trollderiu@gmail.com" --ssl -u trollderiu@gmail.com:\&MOVy1PV -k --anyauth');
