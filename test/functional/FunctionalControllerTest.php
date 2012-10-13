<?php
/**
 * Controller tests
 */
class FunctionalControllerTest extends PHPUnit_Framework_TestCase
{
    function controllerXmlRequestsProvider()
    {
        return xmlDataProvider();
    }

    function controllerJsonRequestsProvider()
    {
        return jsonDataProvider();
    }

    function controllerInvalidRequestsProvider()
    {
        return invalidDataProvider();
    }

    /**
     * @dataProvider controllerInvalidRequestsProvider
     * @param $request
     * @param $expected
     */
    function testControllerInvalidRequests($request, $expected)
    {
        $controller = new LAAF_Controller();
        $given = "GIVEN";

        try {
            $given = $this->curl->post(TEST_SERVICE_URL, $request);
            $given = preg_replace("|(<laaf:datetime)>.*?(</laaf:datetime>)|", '$1' . '>' . $this->dtxml . '$2', $given);
            $given = preg_replace("|(<laaf:line)>.*?(</laaf:line>)|s", '$1' . '>0$2', $given);
            $given = preg_replace("|(<laaf:file)>.*?(</laaf:file>)|s", '$1' . '>file$2', $given);
        } catch (Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }

        $this->assertEquals($expected, $given);
    }

    /**
     * @dataProvider controllerXmlRequestsProvider
     * @param $request
     * @param $expected
     */
    function testControllerXmlRequests($request, $expected)
    {
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
