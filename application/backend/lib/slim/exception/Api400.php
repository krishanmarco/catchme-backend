<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 17/09/2017 - Fithancer © */

namespace Slim\Exception;
use Api\ExceptionResponse as ApiExceptionResponse;
use Exception;
use R;

class Api400 extends Exception implements IApiException {

    public function __construct($code = R::return_error_generic, $apiResponse = null, $previous = null) {
        parent::__construct(basename($this->getFile(), '.php') . " {$this->getLine()}", $code, $previous);
        $this->apiResponse = $apiResponse;
    }

    /** @var Object $apiResponse */
    private $apiResponse;


    public function getHttpCode() {
        return 400;
    }

    public function getAsApiExceptionResponse() {
        $localException = new ApiExceptionResponse();
        $localException->errorCode = $this->getCode();
        $localException->logMessage = $this->getMessage();
        $localException->apiResponse = $this->apiResponse;
        return $localException;
    }

}