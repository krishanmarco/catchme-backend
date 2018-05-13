<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Routes\Accessors;
use Controllers\ControllerAdmin;
use User as DbUser;

class ControllerAccessorAuthAdmin {

    public function __construct(DbUser $authAdmin) {
        $this->authAdmin = $authAdmin;
    }

    /** @var DbUser $authAdmin */
    private $authAdmin;

    public function asAdmin() {
        return new ControllerAdmin($this->authAdmin);
    }

}