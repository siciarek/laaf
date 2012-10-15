<?php

@include_once __DIR__ . '/../lib/LAAF/bootstrap.php';

$controller = new LAAF_Controller();
$response = $controller->actionIndex($request);

$header = "Content-type: " . $response["mimetype"];
$output = $response["output"];
