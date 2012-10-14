#!/usr/bin/env php
<?php

$response = array(
    "mimetype" => "application/xml",
    "output" => "<laaf:frame>
<laaf:success>1</laaf:success>

</laaf:frame>",
);

echo "Content-type: " . $response["mimetype"] . "\n\n";
echo $response["output"];
