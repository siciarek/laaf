<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jsiciarek
 * Date: 12.10.12
 * Time: 16:54
 * To change this template use File | Settings | File Templates.
 */
/**
 * Controller class
 * @package LAAF
 */
class LAAF_Format
{
    private $formats = array();

    public function __construct()
    {
        $this->formats = array(
            "application/json" => function ($input) {
                //$data = json_decode($input);
                return json_decode($input) !== null;
            },
            "application/xml"  => function ($input) {
                $dom = new DOMDocument();
                @$result = $dom->loadXml($input);

                return $result === true;
            }
        );
    }

    public function recognize($input)
    {
        foreach ($this->formats as $mimetype => $function) {
            if ($function($input) === true) {
                return $mimetype;
            }
        }

        return null;
    }

}

class LAAF_Controller
{
    public static function actionIndex($request)
    {
        if (!empty($request)) {

            $format   = new LAAF_Format();
            $mimetype = $format->recognize($request);

            try {
                if ($mimetype === null) {
                    throw new Exception("Invalid request format");
                }

                $reader = new LAAF_Reader_Xml();
                $writer = new LAAF_Writer_Xml();

                if($mimetype === "application/json") {
                    $reader = new LAAF_Reader_Json();
                    $writer = new LAAF_Writer_Json();
                }

                $input = $reader->read($request);
                $dispatcher = new LAAF_Dispatcher();
                $frame  = $dispatcher->assignService($input["msg"], $input["data"]);
                $output = $writer->format($frame);
                $mimetype = $writer->getMimeType();
            } catch (Exception $e) {
                $msg             = strip_tags($e->getMessage());
                $data            = array();
                $data["message"] = $msg;
                $data["code"]    = $e->getCode();
                $data["file"]    = $e->getFile();
                $data["line"]    = $e->getLine();

                $errframe = LAAF_Frame::getError($msg, $data);
                $writer   = new LAAF_Writer_Xml();
                $output   = $writer->format($errframe, false);
                $mimetype = $writer->getMimeType();

            }
        } else {
            $msg = "LAAF - Light As A Feather Webservice Protocol";

            $data["author"] = "Jacek Siciarek <siciarek@gmail.com>";
            $data["repo"]   = "https://github.com/siciarek/laaf";
            $data["server"] = $_SERVER;

            $frame  = LAAF_Frame::getInfo($msg, $data);
            $writer = new LAAF_Writer_Xml();
            $output = $writer->format($frame, false);
            $mimetype = $writer->getMimeType();
        }

        header("Content-type: " . $mimetype);
        echo $output;
        exit;
    }
}
