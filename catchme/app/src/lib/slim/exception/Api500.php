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
        $apiExceptionResponse->errors = null;

        if (DEVELOPMENT_MODE && !is_null($this->getPrevious())) {
            $apiExceptionResponse->_ = strtr('{c} {f} {l} {m}', [
                '{c}' => $this->getPrevious()->getCode(),
                '{f}' => $this->getPrevious()->getFile(),
                '{l}' => $this->getPrevious()->getLine(),
                '{m}' => $this->getPrevious()->getMessage(),
            ]);
        }

        return $apiExceptionResponse;
    }

}