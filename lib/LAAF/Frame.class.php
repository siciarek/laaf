<?php
/**
 * LAAF/Frame.class.php
 */
/**
 * LAAF - Light As A Feather Webservice Protocol
 * Protocol frames provider
 *
 * @author Jacek Siciarek <siciarek@gmail.com>
 * @package LAAF
 */
class LAAF_Frame
{
    /**
     * @var array User authentication data
     */
    public static $auth = array();

    /**
     * @var string Frame datetime format
     */
    public static $date_format = "Y-m-d H:i:s";

    /**
     * @var string|null Fixed frame datetime
     */
    public static $datetime = null;

    /**
     * User authentication data setter
     * @param array $auth User authentication data
     */
    public static function setAuth($auth)
    {
        self::$auth = $auth;
    }

    /**
     * Returns request frame
     *
     * @param $msg Name of the request, name of the service is recommended, ie. "GetUsersList"
     * @param mixed $data [optional] Request data, object containing request parameters is recommended.
     * @return array
     */
    public static function getRequest($msg, $data = null)
    {
        return self::getFrame($msg, "request", $data);
    }

    /**
     * Returns info frame
     *
     * @param $msg [optional] Info frame message ie. "OK"
     * @param mixed $data [optional] object containing some data (key, value format is recommended).
     * @return array
     */
    public static function getInfo($msg = "OK", $data = null)
    {
        return self::getFrame($msg, "info", $data);
    }

    /**
     * Returns warning frame
     *
     * @param $msg Warning message ie. "No result found."
     * @param mixed $data [optional] object containing some data (key, value format is recommended).
     * @return array
     */
    public static function getWarning($msg, $data = null)
    {
        return self::getFrame($msg, "warning", $data);
    }

    /**
     * Returns warning frame
     *
     * @param $msg [optional] Error message ie. "Unexpected error"
     * @param mixed $data [optional] object containing some data (key, value format is recommended).
     * @return array
     */
    public static function getError($msg = "Unexpected error", $data = null)
    {
        return self::getFrame($msg, "error", $data);
    }

    /**
     * Returns data frame
     *
     * @param $msg Data frame message ie. "Users list"
     * @param mixed $data [optional] object containing some data (key, value format is recommended).
     * @return array
     */
    public static function getData($msg, $data = array())
    {
        return self::getFrame($msg, "data", $data);
    }

    /**
     * Returns datetime
     *
     * @return string
     */
    private static function getDateTime()
    {
        if (self::$datetime !== null and self::$datetime === date(self::$date_format, strtotime(self::$datetime))) {
            return self::$datetime;
        }

        return date(self::$date_format);
    }

    /**
     * Returns frame
     *
     * @param $msg string Frame message
     * @param $type string Frame content (request|info|warning|error|data) is valid
     * @param $data array Additional data
     * @return array LAAF Frame structure
     * @throws Exception
     */
    private static   function getFrame($msg, $type, $data)
    {
        if ($data === null) {
            $data = new stdClass();
        }

        if (empty($msg)) {
            throw new Exception("No message was given");
        }

        $success = in_array($type, array("error", "warning")) ? false : true;

        $frame = array(
            "success"  => $success,
            "type"     => $type,
            "datetime" => self::getDateTime(),
            "msg"      => $msg,
        );

        if (self::$auth !== array()) {
            $frame["auth"] = self::$auth;
        }

        if ($type === "data") {
            $frame["totalCount"] = count($data);
        }

        $frame["data"] = $data;

        return $frame;
    }
}
