<?php
/**
 * LAAF/Writer/Json.class.php
 */
/**
 * LAAF Protocol frames JSON writer
 *
 * @author Jacek Siciarek <siciarek@gmail.com>
 * @package LAAF
 * @subpackage Writer
 */
class LAAF_Writer_Json extends LAAF_Writer_Abstract
{
    /**
     * @var string JSON format mime type.
     */
    protected $mime_type = "application/json";

    /**
     * Returns LAAF frame in JSON format
     *
     * @param $data array frame data
     * @return string|void frame in JSON format
     * @throws Exception when xml schema validation fails
     */
    public function format($data) {

        try {
            // For validation against XML Schema
            $xmlw = new LAAF_Writer_Xml();
            $xmlw->format($data);
        }
        catch(Exception $e) {
            throw $e;
        }

        if (array_key_exists("data", $data) and ($data["data"] === array() or empty($data["data"]))) {
            $data["data"] = new stdClass();
        }

        if (array_key_exists("data", $data) and array_key_exists("entity", $data["data"])) {
            $data["data"] = $data["data"]["entity"];
        }

        return json_encode($data);
   }
}
