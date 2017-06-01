<?php

//https://stackoverflow.com/questions/277224/how-do-i-catch-a-php-fatal-error

register_shutdown_function("fatal_handler");

function fatal_handler() {
    //ONLY IN LOCALHOST
    $whitelist = array(
        '127.0.0.1',
        '::1'
    );
    if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
        return;
    }

    $errfile = "unknown file";
    $errstr = "shutdown";
    $errno = E_CORE_ERROR;
    $errline = 0;

    $error = error_get_last();

    if ($error !== NULL) {
        $errno = $error["type"];
        $errfile = $error["file"];
        $errline = $error["line"];
        $errstr = $error["message"];

        echo format_error($errno, $errstr, $errfile, $errline);
    }
}

function format_error($errno, $errstr, $errfile, $errline) {
    $trace = print_r(debug_backtrace(false), true);

    $content = "
        <table>
        <thead><th>Item</th><th>Description</th></thead>
        <tbody>
        <tr>
          <th>Error</th>
          <td><pre>$errstr</pre></td>
        </tr>
        <tr>
          <th>Errno</th>
          <td><pre>$errno</pre></td>
        </tr>
        <tr>
          <th>File</th>
          <td>$errfile</td>
        </tr>
        <tr>
          <th>Line</th>
          <td>$errline</td>
        </tr>
        <tr>
          <th>Trace</th>
          <td><pre>$trace</pre></td>
        </tr>
        </tbody>
        </table>";

    return $content;
}
