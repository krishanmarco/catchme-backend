<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 17/09/2017 */

namespace Slim\Exception;

use Api\ExceptionResponse as ApiExceptionResponse;
use Exception;
use R;

class Api400 extends Exception implements IApiException {

    public function __construct($code = R::return_error_generic, $errors = null, Exception $previous = null) {
        parent::__construct(basename($this->getFile(), '.php') . " {$this->getLine()}", $code, $previous);
        $this->errors = $errors;
        $this->exception = $previous;
    }

    /** @var Object $errors */
    private $errors;

    /** @var Exception|null $previous */
    private $exception;


    public function getHttpCode() {
        return 400;
    }

    public function getAsApiExceptionResponse() {
        $localException = new ApiExceptionResponse();
        $localException->errorCode = $this->getCode();
        $localException->logMessage = $this->getMessage();
        $localException->errors = $this->errors;

        if (DEVELOPMENT_MODE && !is_null($this->exception)) {
            $localException->_ = strtr('{c} {m}', [
                '{c}' => $this->exception->getCode(),
                '{m}' => $this->exception->getMessage(),
            ]);
        }

        return $localException;
    }

}