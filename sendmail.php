<?php

$from = "would-you-rather";
$title = $_POST['title'];
$msg = $_POST['msg'];

//echo -e "From: $from \nTo: Would You Rather \nSubject:$title \n$msg"  | curl smtps://smtp.gmail.com:465 --mail-rcpt "trollderiu@gmail.com" --ssl -u trollderiu@gmail.com:\&MOVy1PV -k --anyauth
exec('echo -e "From: ' . $from . ' \nTo: Would You Rather \nSubject:' . $title . ' \n' . $msg . '"  | curl smtps://smtp.gmail.com:465 --mail-rcpt "trollderiu@gmail.com" --ssl -u trollderiu@gmail.com:\&MOVy1PV -k --anyauth');


//IF 'Authentication failed: 534'
//https://stackoverflow.com/questions/20337040/gmail-smtp-debug-error-please-log-in-via-your-web-browser
//(http://www.google.com/accounts/DisplayUnlockCaptcha)
        