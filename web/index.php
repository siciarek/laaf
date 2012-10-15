<?php

$request = file_get_contents("php://input");

include_once __DIR__ . '/index.inc';

header("Content-length: " . $length);
header($header);
print($output);
