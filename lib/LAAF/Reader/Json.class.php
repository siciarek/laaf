<?php
/**
 * LAAF/Reader/Json.class.php
 */
/**
 * LAAF Protocol frames JSON Reader
 *
 * @author Jacek Siciarek <siciarek@gmail.com>
 * @package LAAF
 * @subpackage Reader
 */

class LAAF_Reader_Json extends LAAF_Reader_Abstract
{
    /**
     * Returns LAAF data
     * @param string $input JSON encoded data
     * @return array|void LAAF frame
     * @throws Exception when xml schema validation fails
     */
    public function read($input)
    {

        $errors = array(
            JSON_ERROR_NONE           => "OK",
            JSON_ERROR_DEPTH          => "Depth error",
            JSON_ERROR_CTRL_CHAR      => "Control character error",
            JSON_ERROR_STATE_MISMATCH => "State mismatch error",
            JSON_ERROR_SYNTAX         => "Syntax error",
            JSON_ERROR_UTF8           => "UTF encoding error",
        );

        try {
            $data = json_decode($input, true);
            $err  = json_last_error();

            if ($err !== JSON_ERROR_NONE) {
                throw new Exception(get_class($this) . " error: " . $errors[$err]);
            }

            if (array_key_exists("data", $data) and $data["data"] === array()) {
                $data["data"] = new stdClass();
            }

            $xmlw = new LAAF_Writer_Xml();
            $xmlw->format($data);
        } catch (Exception $e) {
            throw $e;
        }

        return $data;
   }
}
