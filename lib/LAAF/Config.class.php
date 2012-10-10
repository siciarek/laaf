<?php

require_once __DIR__ . "/../vendor/XML_Serializer-0.20.2/XML/Serializer.php";
require_once __DIR__ . "/../vendor/XML_Serializer-0.20.2/XML/Unserializer.php";
require_once __DIR__ . "/Frame.class.php";
require_once __DIR__ . "/Writer/Abstract.class.php";
require_once __DIR__ . "/Writer/Json.class.php";
require_once __DIR__ . "/Writer/Xml.class.php";
require_once __DIR__ . "/Reader/Abstract.class.php";
require_once __DIR__ . "/Reader/Json.class.php";
require_once __DIR__ . "/Reader/Xml.class.php";

class Config
{
    public static function getSchema()
    {
        $temp = array(
            dirname(__FILE__),
            "..",
            "..",
            "web",
            "schema",
            "frame.xsd"
        );

        $schema = implode(DIRECTORY_SEPARATOR, $temp);
        $schema = str_replace(DIRECTORY_SEPARATOR, '/', $schema);

        return $schema;
    }
}
