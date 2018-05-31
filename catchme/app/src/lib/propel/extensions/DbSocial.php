<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 20/08/2017 - Fithancer © */

class DbSocial {

    public static function facebook($userNetworkId) {
        return new DbSocial(ESocial::facebook, $userNetworkId);
    }

    public static function googlePlus($userNetworkId) {
        return new DbSocial(ESocial::googlePlus, $userNetworkId);
    }




    private function __construct($networkId, $userNetworkId) {
        $this->networkId = $networkId;
        $this->userNetworkId = $userNetworkId;
    }


    /** @var integer $networkId */
    public $networkId;
    /** @var string $userNetworkId */
    public $userNetworkId;


}