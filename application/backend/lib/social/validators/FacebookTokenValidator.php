<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Catch Me 1.0 © */

use Slim\Exception\ApiException;

class FacebookTokenValidator {

    public function __construct(FacebookToken $facebookToken) {
        $this->debugToken = $facebookToken->facebookDebugToken;
        $this->userDataToken = $facebookToken->facebookUserDataToken;
    }


    /** @var FacebookDebugToken $debugToken */
    private $debugToken;
    public function getDebugToken() { return $this->debugToken; }

    /** @var FacebookUserDataToken $userDataToken */
    private $userDataToken;
    public function getUserDataToken() { return $this->userDataToken; }


    public function validate() {

        // Error and authenticity checking
        if($this->debugToken->hasError() || $this->userDataToken->hasError())
            throw new ApiException(R::return_error_invalid_social_token);

        if (!$this->debugToken->isTokenAuthentic() || !$this->userDataToken->isTokenAuthentic())
            throw new ApiException(R::return_error_invalid_social_token);
    }


}