<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jsiciarek
 * Date: 08.10.12
 * Time: 19:53
 * To change this template use File | Settings | File Templates.
 */
abstract class LAAF_Writer_Abstract
{
    protected $mime_type = "application/octet-stream";

    public function getMimeType() {
        return $this->mime_type;
    }

    public function format($data) {
        $msg = sprintf("Function %s() should be implemented in class %s.", __FUNCTION__, get_class($this));
        throw new Exception($msg);
    }
}
