<?php

define("TEST_SERVICE_URL", "http://laaf.dev");
define("FIXTURES_DIR", __DIR__ . "/fixtures");

if(defined("PHP_BINARY") === false)
{
    define("PHP_BINARY", "php");
}

include_once __DIR__ . "/../lib/LAAF/bootstrap.php";
include_once __DIR__ . "/tools/CurlRequest.class.php";
