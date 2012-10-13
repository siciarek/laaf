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
        if(!preg_match("/^(<|\{)/", $input)) {
            return null;
        }

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
    public function actionIndex($request)
    {

        if (!empty($request)) {

            $format   = new LAAF_Format();
            $mimetype = $format->recognize($request);

            try {
                if ($mimetype === null) {
                    throw new LAAF_Exception_InvalidRequestFormat();
                }

                if($mimetype === "application/json") {
                    $reader = new LAAF_Reader_Json();
                    $writer = new LAAF_Writer_Json();
                }
                else {
                    $reader = new LAAF_Reader_Xml();
                    $writer = new LAAF_Writer_Xml();
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
            $msg = "LAAF Service";

            $data["author"] = "Jacek Siciarek <siciarek@gmail.com>";
            $data["repo"]   = "https://github.com/siciarek/laaf";
            $data["description"] = "Light As A Feather Webservice Protocol";

            $frame  = LAAF_Frame::getInfo($msg, $data);
            $writer = new LAAF_Writer_Xml();
            $output = $writer->format($frame, false);
            $mimetype = $writer->getMimeType();
        }

//        return array(
//            "mimetype" => "application/octet-stream",
//            "output" => sprintf("--%s--", $request),
//        );

        return array(
            "mimetype" => $mimetype,
            "output" => $output,
        );
    }
}
