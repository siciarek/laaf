<?php
/**
 * LAAF Protocol frames JSON writer
 */
class LAAF_Writer_Json extends LAAF_Writer_Abstract
{
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

        return json_encode($data);
   }
}
