<?php
/**
 * LAAF/Dispatcher.class.php
 */
/**
 * Inner services dispatcher (controller)
 *
 * @author Jacek Siciarek <siciarek@gmail.com>
 * @package LAAF
 */
class LAAF_Dispatcher
{
    /**
     * Services map array
     * @var array
     */
    private $map = array();

    private function work($service, $data)
    {
        $details = $this->map[$service];

        $class   = $details["class"];
        $method  = $details["method"];
        $params  = $details["params"];
        $returns = $details["returns"];

        $obj = new $class();

        $msg = $service;

        if($params === null || $params === array()) {
            $result = $obj->$method();
        }
        else
        {
            $p = array();

            foreach($params as $key => $value) {
                $rx = sprintf("/%s/", $value);
                if(!preg_match($rx, $data[$key])) {
                    $message = sprintf("Parameter %s=\"%s\" does not match %s", $key, $data[$key], $rx);
                    throw new LAAF_Exception($message);
                }
                $p[] = $data[$key];
            }

            $result = call_user_func_array(array($obj, $method), $p);
        }

        switch ($returns) {
            case "array":
                return LAAF_Frame::getData($msg, $result);
                break;
            case "object":
                return LAAF_Frame::getInfo($msg, $result);
                break;
            default:
                return LAAF_Frame::getInfo($msg);
                break;
        }
    }

    /**
     * class constructor
     */
    public function __construct()
    {
        $this->map = Config::getServiceMap();
    }

    public function assignService($service, $data)
    {

        if (!array_key_exists($service, $this->map)) {
            throw new LAAF_Exception_ServiceNotSupported($service);
        }

        return $this->work($service, $data);
    }
}
