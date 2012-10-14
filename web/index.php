<?php

@include_once __DIR__ . '/../lib/LAAF/bootstrap.php';

$request = file_get_contents("php://input");
$controller = new LAAF_Controller();
$response = $controller->actionIndex($request);

header("Content-type: " . $response["mimetype"]);
echo $response["output"];
