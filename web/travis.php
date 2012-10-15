#!/usr/bin/env php
<?php
$request = file_get_contents("php://stdin", false, null, 0, intval($_SERVER["CONTENT_LENGTH"]));

include_once __DIR__ . '/index.inc';

print($header . "\n\n");
print($output);
