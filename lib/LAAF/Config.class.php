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
    const NS = "http://laaf.siciarek.pl";
    const NS_PREFIX = "laaf";

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
