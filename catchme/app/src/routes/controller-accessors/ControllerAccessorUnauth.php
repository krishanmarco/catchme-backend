<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Routes\Accessors;
use Controllers\ControllerAccounts;
use Controllers\ControllerMediaGet;


class ControllerAccessorUnauth {

    public function __construct() {
        // This controller doesn't need any parameters
    }


    public function accounts() {
        return new ControllerAccounts();
    }

    public function mediaGet() {
        return new ControllerMediaGet();
    }

}