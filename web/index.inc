<?php

@include_once __DIR__ . '/../lib/LAAF/bootstrap.php';

$ext = $_GET;

$auth = array(
    "user" => null,
    "pass" => null,
);

if(array_key_exists("PHP_AUTH_USER", $_SERVER) and array_key_exists("PHP_AUTH_PW", $_SERVER)) {
    $auth["user"] = $_SERVER["PHP_AUTH_USER"];
    $auth["pass"] = $_SERVER["PHP_AUTH_PW"];
}

$controller = new LAAF_Controller();
$response = $controller->actionIndex($request, $ext, $auth);

$headers = array(
    "Content-type" => $response["mimetype"],
    "Content-length" => strlen($response["output"]),
);

process_headers($headers);
print($response["output"]);
