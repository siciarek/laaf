<?php
/**
 * @codeCoverageIgnore
 */
class FrameTest extends PHPUnit_Framework_TestCase
{
    function testException()
    {
        try {
            $frame = LAAF_Frame::getWarning(null);
            throw Exception("No exception was thrown");
        }
        catch(Exception $e) {
            $this->assertEquals("No message was given", $e->getMessage());
        }
    }

    function framesDataProvider()
    {
        return array(
            array(true, "OK", "info", new stdClass()),
            array(true, "Process Complete", "info", new stdClass()),
            array(true, "GetUser", "request", array("id" => 45)),
            array(false, "Unexpected Exception", "error", new stdClass()),
            array(false, "File not found", "error", array("message" => "File not found", "code" => 450)),
            array(false, "No result was found", "warning", new stdClass()),
            array(true, "Users list", "data", array()),
        );
    }

    /**
     * @dataProvider framesDataProvider
     */
    function testFrames($success, $msg, $type, $data)
    {
        $datetime             = "1966-10-21 15:10:00";
        LAAF_Frame::$datetime = $datetime;

        $expected = array(
            "success"  => $success,
            "type"     => $type,
            "datetime" => $datetime,
            "msg"      => $msg,
        );

        if ($type === "data") {
            $expected["totalCount"] = count($data);
        }

        $expected["data"] = $data;


        $given = null;

        switch ($type) {
            case "request":
                $given = LAAF_Frame::getRequest($msg, $data);
                break;
            case "info":
                $given = LAAF_Frame::getInfo($msg, $data);
                break;
            case "warning":
                $given = LAAF_Frame::getWarning($msg, $data);
                break;
            case "error":
                $given = LAAF_Frame::getError($msg, $data);
                break;
            case "data":
                $given = LAAF_Frame::getData($msg, $data);
                break;
            default:
                break;
        }

        $this->assertEquals($expected, $given);
    }

    function testDatetime() {
        $datetime             = "1966-10-21 15:10:00";

        $frame = LAAF_Frame::getInfo();
        $this->assertNull(LAAF_Frame::$datetime);

        $frame = LAAF_Frame::getInfo();
        $this->assertNotEquals($datetime, $frame["datetime"]);
        $this->assertEquals(time(), strtotime($frame["datetime"]));

        LAAF_Frame::$datetime = $datetime;
        $frame = LAAF_Frame::getInfo();
        $this->assertEquals($datetime, $frame["datetime"]);

        LAAF_Frame::$datetime = null;
        $frame = LAAF_Frame::getInfo();
        $this->assertNotEquals($datetime, $frame["datetime"]);
        $this->assertEquals(time(), strtotime($frame["datetime"]));
    }

    function testAuth()
    {
        $auth = array(
            "username" => "johndoe",
            "password" => "secret",
        );

        $info = LAAF_Frame::getInfo();
        $this->assertArrayNotHasKey("auth", $info);

        LAAF_Frame::setAuth($auth);

        $info = LAAF_Frame::getInfo();
        $this->assertArrayHasKey("auth", $info);

        $this->assertEquals($auth, $info["auth"]);
    }

    function testDefaults()
    {
        $info  = LAAF_Frame::getInfo();
        $error = LAAF_Frame::getError();

        $this->assertEquals("OK", $info["msg"]);
        $this->assertEquals("Unexpected error", $error["msg"]);
    }

    function setUp() {
        LAAF_Frame::$datetime = null;
    }
}
