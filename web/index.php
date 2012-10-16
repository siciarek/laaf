<?php

function process_header($name, $value) {
    $header = sprintf("%s: %s", $name, $value);
    header($header);
}

$request = file_get_contents("php://input");

include_once __DIR__ . '/index.inc';

