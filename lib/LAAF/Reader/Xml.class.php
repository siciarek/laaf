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
     * @throws Exception when xml schema validation fails
     */
    public function read($input)
    {
        $xml = new DOMDocument("1.0", "UTF-8");
        $xml->loadXML($input);

        try {
            $xml->schemaValidate(Config::getSchema());
        } catch (Exception $e) {
            throw $e;
        }

        $input = $xml->saveXML();

        $options = array(
            XML_UNSERIALIZER_OPTION_COMPLEXTYPE => 'array',
            XML_UNSERIALIZER_OPTION_FORCE_ENUM => array('entity'),
        );

        $unserializer = new XML_Unserializer();

        $status       = $unserializer->unserialize($input, false, $options);
        $data         = $unserializer->getUnserializedData();

        $data["success"]  = $data["success"] === "1";
        $data["datetime"] = preg_replace("/T/", " ", $data["datetime"]);

        if (array_key_exists("totalCount", $data)) {
            $data["totalCount"] = intval($data["totalCount"]);
        }

        if (array_key_exists("data", $data) and ($data["data"] === array() or empty($data["data"]))) {
            $data["data"] = new stdClass();
        }

        if(array_key_exists("entity", $data["data"])) {
            $data["data"] = $data["data"]["entity"];
        }

        // Check if something went wrong during the serialization:
        $xmlw = new LAAF_Writer_Xml();
        $xmlw->format($data);

        return $data;
    }
}
