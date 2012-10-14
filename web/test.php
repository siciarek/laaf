#!/usr/bin/env php
<?php

@include_once __DIR__ . '/../lib/LAAF/bootstrap.php';

$request = file_get_contents("php://stdin", false, null, 0, intval($_SERVER["CONTENT_LENGTH"]));

$controller = new LAAF_Controller();
$response = $controller->actionIndex($request);

echo "Content-type: " . $response["mimetype"] . "\n\n";
echo $response["output"];
