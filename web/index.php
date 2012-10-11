<?php

include_once __DIR__ . '/../lib/LAAF/bootstrap.php';

$msg = "LAAF - Light As A Feather Webservice Protocol";

$data["author"] = "Jacek Siciarek <siciarek@gmail.com>";
$data["repo"]   = "https://github.com/siciarek/laaf";
$data["server"] = $_SERVER;

$frame  = LAAF_Frame::getInfo($msg, $data);

$request = file_get_contents("php://input");

$reader = new LAAF_Reader_Xml();
$writer = new LAAF_Writer_Xml();

if (array_key_exists("CONTENT_TYPE", $_SERVER) and $_SERVER["CONTENT_TYPE"] === "application/json") {
    $reader = new LAAF_Reader_Json();
    $writer = new LAAF_Writer_Json();
}
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

if(!empty($request)) {

    try {
        $input = $reader->read($request);

        $msg = "Your request is valid";
        $data = array(
            "request" => $input
        );

        $dispatcher = new LAAF_Dispatcher();
        $frame  = $dispatcher->assignService($input["msg"], $input["data"]);
    }
    catch(Exception $e) {
        $msg = strip_tags($e->getMessage());
        $data = array();
        $data["message"] = $msg;
        $data["code"] = $e->getCode();
        $data["file"] = $e->getFile();
        $data["line"] = $e->getLine();
        $frame  = LAAF_Frame::getError($msg, $data);
    }
}

header("Content-type: " . $writer->getMimeType());
echo $writer->format($frame);
