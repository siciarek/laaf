<?php

include_once __DIR__ . '/../lib/LAAF/Config.class.php';

$msg = "LAAF - Light As A Feather Webservice Protocol";

$data["author"] = "Jacek Siciarek <siciarek@gmail.com>";
$data["repo"]   = "https://github.com/siciarek/laaf";
$data["server"] = $_SERVER;

$frame  = LAAF_Frame::getInfo($msg, $data);
$writer = new LAAF_Writer_Xml();

if (array_key_exists("CONTENT_TYPE", $_SERVER) and $_SERVER["CONTENT_TYPE"] === "application/json") {
    $writer = new LAAF_Writer_Json();
}

header("Content-type: " . $writer->getMimeType());
echo $writer->format($frame);
