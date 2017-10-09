<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 17/09/2017 - Fithancer © */

namespace Slim\Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Api\ExceptionResponse as ApiExceptionResponse;
use R;

class ApiExceptionHandler {

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $exception) {

        if ($exception instanceof ApiException)
            return $this->handleApiException($response, $this->apiExceptionResponse($exception));

        return $this->handleUnknownException($response, $exception);
    }


    private function apiExceptionResponse(ApiException $apiException) {
        $localException = new ApiExceptionResponse();
        $localException->errorCode = $apiException->getCode();
        $localException->logMessage = $apiException->getMessage();
        return $localException;
    }

    private function handleUnknownException(ResponseInterface $response, $exception) {
        $localException = new ApiExceptionResponse();
        $localException->errorCode = R::return_error_generic;
        $localException->logMessage = strtr('{0} {1}', ['{0}' => basename(__FILE__, '.php'), '{1}' => __LINE__]);
        return $this->handleApiException($response, $localException);
    }

    private function handleApiException(ResponseInterface $response, ApiExceptionResponse $exception) {

        if (!DEVELOPMENT_MODE)
            $exception->logMessage = null;

        $response = $response->withStatus(500);
        $response->getBody()->write(json_encode($exception));
        return $response;
    }


}