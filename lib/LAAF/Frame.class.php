<?php
/**
 * Frame.class.php
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
     * @param mixed $data Request data, object containing request parameters is recommended.
     * @return array
     */
    public static function getRequest($msg, $data = null)
    {
        return self::getFrame($msg, "request", $data);
    }

    public static function getInfo($msg = "OK", $data = null)
    {
        return self::getFrame($msg, "info", $data);
    }

    public static function getWarning($msg, $data = null)
    {
        return self::getFrame($msg, "warning", $data);
    }

    public static function getError($msg = "Unexpected error", $data = null)
    {
        return self::getFrame($msg, "error", $data);
    }

    public static function getData($msg, $data = array())
    {
        return self::getFrame($msg, "data", $data);
    }

    private static function getDateTime()
    {
        if (self::$datetime !== null and self::$datetime === date(self::$date_format, strtotime(self::$datetime))) {
            return self::$datetime;
        }

        return date(self::$date_format);
    }

    private function getFrame($msg, $type, $data)
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
