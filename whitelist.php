<?php

$whitelist = array(
    '127.0.0.1',
    '::1'
);
$server_whitelist = array(
    '149.56.98.6'
);

$whitelisted = false;
if (isset($_SERVER['REMOTE_ADDR']) && isset($_SERVER['SERVER_ADDR']) && //if manually on server
        (in_array($_SERVER['REMOTE_ADDR'], $whitelist) || in_array($_SERVER['SERVER_ADDR'], $server_whitelist))) {
    $whitelisted = true;
}
