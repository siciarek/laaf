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
     * @throws LAAF_Exception
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
                throw new LAAF_Exception("JSON error: " . $errors[$err]);
            }

            if (array_key_exists("data", $data) and ($data["data"] === array() or empty($data["data"]))) {
                $data["data"] = new stdClass();
            }

            if (array_key_exists("data", $data) and array_key_exists("entity", $data["data"])) {
                $data["data"] = $data["data"]["entity"];
            }

            $xmlw = new LAAF_Writer_Xml();
            $xmlw->format($data);
        } catch (Exception $e) {
            throw $e;
        }

        return $data;
   }
}
