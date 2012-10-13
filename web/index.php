<?php

include_once __DIR__ . '/../lib/LAAF/bootstrap.php';

$msg = "LAAF - Light As A Feather Webservice Protocol";

$data["author"] = "Jacek Siciarek <siciarek@gmail.com>";
$data["repo"]   = "https://github.com/siciarek/laaf";
$data["server"] = $_SERVER;

$frame  = LAAF_Frame::getInfo($msg, $data);

$request = file_get_contents("php://input");

$controller = new LAAF_Controller();

$response = $controller->actionIndex($request);

header("Content-type: " . $response["mimetype"]);
echo $response["output"];

/*

errors:

""

"ABC"

"<x>ABC</x>"
// no ns
"<laaf:frame>
<laaf:success>1</laaf:success>

</laaf:frame>"

'<?xml version="1.0" encoding="UTF-8"?>
<laaf:frame xmlns:laaf="http://laaf.siciarek.pl">
    <laaf:success>1</laaf:success>
    <laaf:type>request</laaf:type>
    <laaf:datetime>2012-10-11T18:27:33</laaf:datetime>
    <laaf:msg>UsersList</laaf:msg>
    <laaf:data/>
</laaf:frame>'

<laaf:frame xmlns:laaf="http://laaf.siciarek.pl">
    <laaf:success>1</laaf:success>
    <laaf:type>request</laaf:type>
    <laaf:datetime>2012-10-11T18:27:33</laaf:datetime>
    <laaf:msg>UserDetails</laaf:msg>
    <laaf:data>
        <laaf:id>45</laaf:id>
    </laaf:data>
</laaf:frame>

*/

