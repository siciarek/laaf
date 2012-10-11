<?php
/**
 * LAAF/Config.class.php
 */
/**
 * Configuration provider
 *
 * @author Jacek Siciarek <siciarek@gmail.com>
 * @package LAAF
 */
class Config
{
    const NS        = "http://laaf.siciarek.pl";
    const NS_PREFIX = "laaf";

    private static $service_map = null;

    public static function getServiceMap()
    {
        if (self::$service_map === null) {
            $service_map_file  = __DIR__ . "/../Service/config/map.yml";
            $temp              = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($service_map_file));
            self::$service_map = $temp["ServiceMap"];
        }

        return self::$service_map;
    }

    /**
     * Returns path to LAAF XML Schema file.
     *
     * @return string path to LAAF XML Schema file
     */
    public static function getSchema()
    {
        $temp = array(
            dirname(__FILE__),
            "..",
            "..",
            "web",
            "schema",
            "frame.xsd"
        );

        $schema = implode(DIRECTORY_SEPARATOR, $temp);
        $schema = str_replace(DIRECTORY_SEPARATOR, '/', $schema);

        return $schema;
    }
}
