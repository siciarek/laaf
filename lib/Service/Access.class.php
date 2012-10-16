<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jsiciarek
 * Date: 15.10.12
 * Time: 15:22
 * To change this template use File | Settings | File Templates.
 */
class Access implements LAAF_AccessInterface
{
    /**
     * Returns user credentials
     * @param $user
     * @param $pass
     * @return array
     */
    public static function getCredentials($user, $pass) {
        if($user === "colak" and $pass === "helloworld") {
            return array(
                "privileged_user",
            );
        }
        else if($user === "johndoe" and $pass === "matrix") {
            return array(
                "admin",
            );
        }
        else {
            return array(
                "anonymous"
            );
        }
    }
}
