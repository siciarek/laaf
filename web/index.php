<?php

function process_headers($headers) {
    foreach($headers as $name => $value) {
        $header = sprintf("%s: %s", $name, $value);
        header($header);
    }
}

$request = file_get_contents("php://input");

include_once __DIR__ . '/index.inc';

