<?php

$request = file_get_contents("php://input");

include_once __DIR__ . '/index.inc';

header($header);
print($output);
