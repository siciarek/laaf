<?php
/**
 * LAAF/Reader/Abstract.class.php
 */
/**
 * Abstract class for LAAF Readers
 *
 * @author Jacek Siciarek <siciarek@gmail.com>
 * @package LAAF
 * @subpackage Reader
 */
abstract class LAAF_Reader_Abstract
{
    /**
     * Read method should be implemented in child class
     *
     * @param $input string
     * @throws Exception
     */
    public function read($input) {
        $msg = sprintf("Function %s() should be implemented in class %s.", __FUNCTION__, get_class($this));
        throw new Exception($msg);
    }
}
