<?php
/**
 * LAAF/Reader/Xml.class.php
 */
/**
 * LAAF Protocol frames Xml Reader
 *
 * @author Jacek Siciarek <siciarek@gmail.com>
 * @package LAAF
 * @subpackage Reader
 */
class LAAF_Reader_Xml extends LAAF_Reader_Abstract
{
    /**
     * Returns LAAF data
     * @param string $input XML encoded data
     * @return array|void LAAF frame
     * @throws LAAF_Exception when xml schema validation fails
     */
    public function read($input)
    {
        $xml = new DOMDocument("1.0", "UTF-8");
        @$validxml = $xml->loadXml($input);

        if ($validxml == false) {
           throw new LAAF_Exception_InvalidRequestFormat();
        }

        @$validxml = $xml->schemaValidate(Config::getSchema());

        if ($validxml == false) {
            throw new LAAF_Exception_InvalidRequestFormat();
        }

        $input = $xml->saveXML();

        //

        $data = array();

        $unserializer = new XML_Unserializer();

        $status = $unserializer->unserialize($input);
        $data   = $unserializer->getUnserializedData();

        //

        $data["success"]  = $data["success"] === "1";
        $data["datetime"] = preg_replace("/T/", " ", $data["datetime"]);

        if (array_key_exists("totalCount", $data)) {
            $data["totalCount"] = intval($data["totalCount"]);
        }

        if (array_key_exists("data", $data) and ($data["data"] === array() or empty($data["data"]))) {
            $data["data"] = new stdClass();
        }

        return $data;
    }
}
