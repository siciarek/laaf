#!/usr/bin/env php
<?php

$response = array(
    "mimetype" => "application/xml",
    "output" => "<laaf:frame>
<laaf:success>1</laaf:success>

</laaf:frame>",
);

include_once __DIR__ . '/../lib/LAAF/bootstrap.php';

$request = file_get_contents("php://input");
$controller = new LAAF_Controller();
$response = $controller->actionIndex($request);

echo "Content-type: " . $response["mimetype"] . "\n\n";
echo $response["output"];
