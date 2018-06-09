<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 08/10/2017 */

class FacebookToken {

    public function __construct($tokenString) {
        $this->facebookDebugToken = new FacebookDebugToken($tokenString);
        $this->facebookUserDataToken = new FacebookUserDataToken($tokenString);
    }

    /** @var FacebookDebugToken $facebookDebugToken */
    public $facebookDebugToken;

    /** @var FacebookUserDataToken $facebookUserDataToken */
    public $facebookUserDataToken;

}