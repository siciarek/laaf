<?php
/**
 * For abstact class tests
 */
class Baz extends LAAF_Reader_Abstract
{

}

class ReadersTest extends PHPUnit_Framework_TestCase
{
    private $datetime = "1966-10-21 15:10:00";

    function readerXmlDataProvider()
    {
        LAAF_Frame::$datetime = $this->datetime;

        $users = array(
            array(
                "id"         => 294,
                "first_name" => "Piotr",
                "last_name"  => "Cichacki",
            ),
            array(
                "id"         => 456,
                "first_name" => "Jacek",
                "last_name"  => "Siciarek",
            ),
        );

        return array(

            array(LAAF_Frame::getData("UsersList", $users), '<?xml version="1.0" encoding="UTF-8"?>
<laaf:frame xmlns:laaf="' . Config::NS . '">
    <laaf:success>1</laaf:success>
    <laaf:type>data</laaf:type>
    <laaf:datetime>1966-10-21T15:10:00</laaf:datetime>
    <laaf:msg>UsersList</laaf:msg>
    <laaf:totalCount>2</laaf:totalCount>
    <laaf:data>
        <laaf:entity>
            <laaf:id>294</laaf:id>
            <laaf:first_name>Piotr</laaf:first_name>
            <laaf:last_name>Cichacki</laaf:last_name>
        </laaf:entity>
        <laaf:entity>
            <laaf:id>456</laaf:id>
            <laaf:first_name>Jacek</laaf:first_name>
            <laaf:last_name>Siciarek</laaf:last_name>
        </laaf:entity>
    </laaf:data>
</laaf:frame>
'),

            array(LAAF_Frame::getRequest("GetUser", array("id" => 123)), '<?xml version="1.0" encoding="UTF-8"?>
<laaf:frame xmlns:laaf="' . Config::NS . '">
    <laaf:success>1</laaf:success>
    <laaf:type>request</laaf:type>
    <laaf:datetime>1966-10-21T15:10:00</laaf:datetime>
    <laaf:msg>GetUser</laaf:msg>
    <laaf:data>
        <laaf:id>123</laaf:id>
    </laaf:data>
</laaf:frame>
'),

            array(LAAF_Frame::getInfo(), '<?xml version="1.0" encoding="UTF-8"?>
<laaf:frame xmlns:laaf="' . Config::NS . '">
    <laaf:success>1</laaf:success>
    <laaf:type>info</laaf:type>
    <laaf:datetime>1966-10-21T15:10:00</laaf:datetime>
    <laaf:msg>OK</laaf:msg>
    <laaf:data/>
</laaf:frame>
'),
            array(LAAF_Frame::getError(), '<?xml version="1.0" encoding="UTF-8"?>
<laaf:frame xmlns:laaf="' . Config::NS . '">
    <laaf:success>0</laaf:success>
    <laaf:type>error</laaf:type>
    <laaf:datetime>1966-10-21T15:10:00</laaf:datetime>
    <laaf:msg>Unexpected error</laaf:msg>
    <laaf:data/>
</laaf:frame>
'),
            array(LAAF_Frame::getWarning("No result found."), '<?xml version="1.0" encoding="UTF-8"?>
<laaf:frame xmlns:laaf="' . Config::NS . '">
    <laaf:success>0</laaf:success>
    <laaf:type>warning</laaf:type>
    <laaf:datetime>1966-10-21T15:10:00</laaf:datetime>
    <laaf:msg>No result found.</laaf:msg>
    <laaf:data/>
</laaf:frame>
')
        );
    }

    /**
     * @dataProvider readerXmlDataProvider
     * @param $expected
     */
    function testXml($expected, $input)
    {
        $reader = new LAAF_Reader_Xml();
        $this->assertEquals($expected, $reader->read($input));
    }

    function testXmlException()
    {
        $input = '<?xml version="1.0" encoding="UTF-8"?>
<laaf:frame xmlns:laaf="' . Config::NS . '">
    <laaf:success>4</laaf:success>
    <laaf:type>info</laaf:type>
    <laaf:datetime>1966-10-21T15:10:00</laaf:datetime>
    <laaf:msg>OK</laaf:msg>
    <laaf:data/>
</laaf:frame>
';

        $reader = new LAAF_Reader_Xml();

        try{
            $reader->read($input);
            $this->assertTrue(false, "No exception was thrown.");
        }
        catch(Exception $e) {
            $expected = "DOMDocument::schemaValidate(): "
            . "Element '{" . Config::NS . "}success': '4' "
            . "is not a valid value of the atomic type '{" . Config::NS . "}successType'.";

            $given =
                    $e->getMessage()
//                        . "\n====================================\n"
//                        . $e->getTraceAsString()
//                        . "\n====================================\n"
                ;

            $this->assertEquals($expected, $given, print_r($input, true));
        }
    }

    function testAbstract()
    {
        $w = new Baz();

        try {
            $w->read("");
            $this->assertTrue(false, "No exception was thrown.");
        } catch (Exception $e) {
            $this->assertEquals("Function read() should be implemented in class Baz.", $e->getMessage());
        }
    }

    function readerJsonDataProvider()
    {
        LAAF_Frame::$datetime = $this->datetime;
        return array(
            array(LAAF_Frame::getInfo(), '{"success":true,"type":"info","datetime":"' . $this->datetime . '","msg":"OK","data":{}}')
        );
    }

    /**
     * @dataProvider readerJsonDataProvider
     * @param $expected
     * @param $input
     */
    function testJson($expected, $input)
    {
        $reader = new LAAF_Reader_Json();
        $given  = $reader->read($input);
        $this->assertEquals($expected, $given);
    }

    function readerJsonExceptionsDataProvider()
    {
        return array(
            array("[]", "DOMDocument::schemaValidate(): Element '{" . Config::NS . "}frame': Missing child element(s). Expected is ( {" . Config::NS . "}success )."),
            array('{"a":1', "LAAF_Reader_Json error: Syntax error"),
            array('{"success":true,"type":"info","datetime":"WRONG DATETIME","msg":"OK","data":{}}',
                "DOMDocument::schemaValidate(): Element '{" . Config::NS . "}datetime': 'WRONGTDATETIME' is not a valid value of the atomic type '{" . Config::NS . "}datetimeType'."
            ),
        );
    }

    /**
     * @dataProvider readerJsonExceptionsDataProvider
     */
    function testJsonException($input, $expected)
    {
        $reader = new LAAF_Reader_Json();

        try {
            $reader->read($input);
            $this->assertTrue(false, "No exception was thrown.");
        } catch (Exception $e) {
            $given = $e->getMessage();
            $this->assertEquals($expected, $given);
        }
    }
}
