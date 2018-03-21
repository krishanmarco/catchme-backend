<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 17/09/2017 - Fithancer © */

namespace Slim\Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Api\ExceptionResponse as ApiExceptionResponse;
use R;

class ApiExceptionHandler {
    const unknownExceptionMessage = 'UNKNOWN_EXCEPTION';

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $exception) {

        // Check if the thrown exception is valid, if it is not override it with a valid ApiException
        if (!($exception instanceof IApiException))
            $exception = new Api500(R::return_error_generic, self::unknownExceptionMessage);

        // $exception is now an IApiException
        /** @var IApiException $exception */

        /** @var ApiExceptionResponse $apiExceptionResponse */
        $apiExceptionResponse = $exception->getAsApiExceptionResponse();

        // We never show log messages in production
        if (!DEVELOPMENT_MODE)
            $apiExceptionResponse->logMessage = null;

        // Set the response status
        $response = $response->withStatus($exception->getHttpCode());

        // Write the exception as a json and return the response
        $response->getBody()->write(json_encode($exception));
        return $response;
    }

}