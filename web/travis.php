#!/usr/bin/env php
<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

$qs = preg_replace("/&amp;/", "&", $_SERVER["QUERY_STRING"]);
$temp = explode("&", $qs);

foreach($temp as $pos) {
    $match = array();
    $ok = preg_match("/^([^=]+)=(.*)$/", $pos, $match);

    if($ok) {
        $key = $match[1];
        $value = $match[2];

        $_GET[$key] = $value;
        $_REQUEST[$key] = $value;
    }
}

function process_headers($headers) {
    foreach($headers as $name => $value) {
        $header = sprintf("%s: %s\n", $name, $value);
        print($header);
    }
    print("\n");
}

$request = file_get_contents("php://stdin", false, null, 0, intval($_SERVER["CONTENT_LENGTH"]));

include_once __DIR__ . '/index.inc';
