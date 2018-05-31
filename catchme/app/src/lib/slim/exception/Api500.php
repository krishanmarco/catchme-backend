<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 17/09/2017 - Fithancer © */

namespace Slim\Exception;
use Api\ExceptionResponse as ApiExceptionResponse;
use Exception;
use R;

class Api500 extends Exception implements IApiException {

    public function __construct($code = R::return_error_generic, $previous = null) {
        parent::__construct(basename($this->getFile(), '.php') . " {$this->getLine()}", $code, $previous);
    }

    public function getHttpCode() {
        return 500;
    }

    public function getAsApiExceptionResponse() {
        $apiExceptionResponse = new ApiExceptionResponse();
        $apiExceptionResponse->errorCode = $this->getCode();
        $apiExceptionResponse->logMessage = $this->getMessage();
        $apiExceptionResponse->apiResponse = null;
        return $apiExceptionResponse;
    }

}