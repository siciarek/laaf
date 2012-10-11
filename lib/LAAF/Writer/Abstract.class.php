<?php
/**
 * LAAF/Writer/Abstract.class.php
 */
/**
 * Abstract class for LAAF Writers
 *
 * @author Jacek Siciarek <siciarek@gmail.com>
 * @package LAAF
 * @subpackage Writer
 */
abstract class LAAF_Writer_Abstract
{
    /**
     * @var string General format mime type.
     */
    protected $mime_type = "application/octet-stream";

    /**
     * Returns Writer mime type
     *
     * @return string Mime type of current writer
     */
    public function getMimeType() {
        return $this->mime_type;
    }

    /**
     * Format method should be implemented in child class
     *
     * @param $data array
     * @throws Exception
     */
    public function format($data) {
        $msg = sprintf("Function %s() should be implemented in class %s.", __FUNCTION__, get_class($this));
        throw new Exception($msg);
    }
}
