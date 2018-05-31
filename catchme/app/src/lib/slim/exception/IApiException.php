<?php  /** Created by Krishan Marco Madan on 21-Mar-18. */

namespace Slim\Exception;
use Api\ExceptionResponse as ApiExceptionResponse;

interface IApiException {
    /** @return ApiExceptionResponse */
    public function getAsApiExceptionResponse();

    /** @return int */
    public function getHttpCode();
}