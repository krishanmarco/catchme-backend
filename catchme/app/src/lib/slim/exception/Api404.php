<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 17/09/2017 */

namespace Slim\Exception;

use Api\ExceptionResponse as ApiExceptionResponse;
use Exception;
use R;

class Api404 extends Exception implements IApiException {

    public function __construct($code = R::return_error_generic, Exception $previous = null) {
        parent::__construct(basename($this->getFile(), '.php') . " {$this->getLine()}", $code, $previous);
        $this->exception = $previous;
    }

    /** @var Exception|null $previous */
    private $exception;

    public function getHttpCode() {
        return 404;
    }

    public function getAsApiExceptionResponse() {
        $localException = new ApiExceptionResponse();
        $localException->errorCode = $this->getCode();
        $localException->logMessage = $this->getMessage();
        return $localException;
    }

}