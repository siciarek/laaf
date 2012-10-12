<?php
class WritersTestSecond extends PHPUnit_Framework_TestCase
{
    private $datetime = "1966-10-21 15:10:00";

    function writerXmlDataProvider()
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
     * @dataProvider writerXmlDataProvider
     * @param $expected
     * @group oksecond
     */
    function testXml($data, $expected)
    {
        $w = new LAAF_Writer_Xml();

        try {
            $given = $w->format($data);
        } catch (Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }

        $this->assertEquals($expected, $given);
    }
}
