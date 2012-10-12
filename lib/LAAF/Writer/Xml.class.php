<?php
/**
 * LAAF/Writer/Xml.class.php
 */
/**
 * LAAF Protocol frames XML writer
 *
 * @author Jacek Siciarek <siciarek@gmail.com>
 * @package LAAF
 * @subpackage Writer
 */
class LAAF_Writer_Xml extends LAAF_Writer_Abstract
{
    /**
     * @var string XML format mime type.
     */
    protected $mime_type = "application/xml";

    /**
     * Returns LAAF frame formated in XML
     * @param $data frame data
     * @return string|void frame in XML format
     * @throws Exception when xml schema validation fails
     */
    public function format($data) {

        if(array_key_exists("datetime", $data)) {
            $data["datetime"] = preg_replace("/ /", "T", $data["datetime"]);
        }

        $options = array(
            XML_SERIALIZER_OPTION_XML_ENCODING     => "UTF-8",
            XML_SERIALIZER_OPTION_XML_DECL_ENABLED => true,
            XML_SERIALIZER_OPTION_ROOT_NAME        => "frame",
            XML_SERIALIZER_OPTION_INDENT           => "    ",
            XML_SERIALIZER_OPTION_LINEBREAKS       => "\n",
            XML_SERIALIZER_OPTION_MODE             => XML_SERIALIZER_MODE_SIMPLEXML,
            XML_SERIALIZER_OPTION_NAMESPACE        => array(Config::NS_PREFIX, Config::NS),
        );

        $serializer = new XML_Serializer($options);

        $serializer->serialize($data);
        $temp = $serializer->getSerializedData();


        $frame = preg_replace("#(</?)(" . Config::NS_PREFIX . ":){2}#", "$1".Config::NS_PREFIX . ":", $temp);

        $xml = new DOMDocument("1.0", "UTF-8");
        $xml->loadXML($frame);

        try {
            $xml->schemaValidate(Config::getSchema());
        }
        catch(Exception $e) {
            throw $e;
        }

        return $xml->saveXML();
    }
}
