<?php
/**
 * Controller tests
 */
class ControllerTest extends PHPUnit_Framework_TestCase
{
    function controllerXmlRequestsProvider()
    {
        return array(
            array(
                file_get_contents(FIXTURES_DIR . '/user-list-request.xml'),
                file_get_contents(FIXTURES_DIR . '/user-list-response.xml'),
            ),
            array(
                file_get_contents(FIXTURES_DIR . '/user-details-request.xml'),
                file_get_contents(FIXTURES_DIR . '/user-details-response.xml'),
            ),
        );
    }

    function controllerJsonRequestsProvider()
    {
        return array(
            array(
                file_get_contents(FIXTURES_DIR . '/user-list-request.json'),
                file_get_contents(FIXTURES_DIR . '/user-list-response.json'),
            ),
            array(
                file_get_contents(FIXTURES_DIR . '/user-details-request.json'),
                file_get_contents(FIXTURES_DIR . '/user-details-response.json'),
            ),
        );
    }

    /**
     * @dataProvider controllerXmlRequestsProvider
     * @param $request
     * @param $expected
     */
    function testControllerXmlRequests($request, $expected)
    {
        $w     = new LAAF_Writer_Xml();
        $given = "GIVEN";

        try {
            $given = $this->curl->post(TEST_SERVICE_URL, $request);
            $given = preg_replace("|(<laaf:datetime)>.*?(</laaf:datetime>)|", '$1' . '>' . $this->dtxml . '$2', $given);
        } catch (Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }

        $this->assertEquals($expected, $given);
    }

    /**
     * @dataProvider controllerJsonRequestsProvider
     * @param $request
     * @param $expected
     */
    function testControllerJsonRequests($request, $expected)
    {
        $w     = new LAAF_Writer_Json();
        $given = "GIVEN";
        $expected = json_encode(json_decode($expected, true));

        try {
            $given = $this->curl->post(TEST_SERVICE_URL, $request);
            $given = preg_replace('|("datetime":)"[^"]+"|', '$1"' . $this->datetime . '"', $given);
        } catch (Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }

        $this->assertEquals($expected, $given);
    }

    function setUp()
    {
        $this->curl     = new MyCurl();
        $this->datetime = "1966-10-21 15:10:00";
        $this->dtxml    = "1966-10-21T15:10:00";
    }
}
