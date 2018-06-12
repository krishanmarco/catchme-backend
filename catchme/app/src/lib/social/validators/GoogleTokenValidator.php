<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Catch Me 1.0 © */

use Slim\Exception\Api400;

class GoogleTokenValidator {

    public function __construct(GoogleToken $googleToken) {
        $this->token = $googleToken;
    }


    /** @var GoogleToken $token */
    private $token;

    public function getToken() { return $this->token; }


    public function validate() {

        // Error and authenticity checking
        if ($this->token->hasError())
            throw new Api400(R::return_error_invalid_social_token);

        if (!$this->token->isTokenAuthentic())
            throw new Api400(R::return_error_invalid_social_token);
    }

}