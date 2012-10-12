<?php

require_once __DIR__ . '/XMLSerializer.class.php';


$xml =<<<XML
<frame>
    <success>1</success>
    <type>request</type>
</frame>
XML;

$s = new XMLSerializer(new stdClass());


