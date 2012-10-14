#!/usr/bin/env php
<?php
$request = file_get_contents("php://stdin", false, null, 0, intval($_SERVER["CONTENT_LENGTH"]));

@include_once __DIR__ . '/../lib/LAAF/bootstrap.php';

$controller = new LAAF_Controller();
$response = $controller->actionIndex($request);

$header = "Content-type: " . $response["mimetype"];
$output = $response["output"];


print($header);
print($output);
