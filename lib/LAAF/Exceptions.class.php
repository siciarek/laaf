<?php
/**
 * LAAF/Exception.class.php
 */
/**
 * Generic LAAF Exception
 *
 * @author Jacek Siciarek <siciarek@gmail.com>
 * @package LAAF
 * @subpackage Exception
 */
class LAAF_Exception extends Exception {

}

/**
 * Service not supported LAAF Exception
 *
 * @author Jacek Siciarek <siciarek@gmail.com>
 * @package LAAF
 * @subpackage Exception
 */
class LAAF_Exception_ServiceNotSupported extends LAAF_Exception {
    public function __construct($service) {
        $this->message = sprintf("We are sorry, but service \"%s\" is not supported.", $service);
        $this->code = 2013;
    }
}
