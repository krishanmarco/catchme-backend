<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer 1.0 © */

namespace Slim;
use \Psr\Http\Message\ResponseInterface;
use \Api\ExceptionResponse;


class SlimOutput {

    public static function buildAndWrite(ResponseInterface $response, $backendResult, $asJson = true) {
        $bmo = new SlimOutput($response, $backendResult);
        return $bmo->writeToResponse($asJson);
    }

    public static function build(ResponseInterface $response, $backendResult) {
        return new SlimOutput($response, $backendResult);
    }




    private function __construct(ResponseInterface $response, $backendResult) {
        $this->response = $response;
        $this->backendResult = $backendResult;

        // Call handle error, this should be the first
        // function that is called and it should always
        // be called, so call it from the constructor
        $this->handleError();
    }


    /** @var ResponseInterface $response */
    private $response;
    public function getResponse() { return $this->response; }


    /** @var mixed $backendResult */
    private $backendResult;


    /** @var bool $errorFound */
    private $errorFound = false;
    public function hasError() { return $this->errorFound; }




    private function handleError() {

        // Check for an ApiException, if there is no error, do nothing
        if (!is_object($this->backendResult) || get_class($this->backendResult) != ExceptionResponse::class)
            return $this;


        // The backend response has an error,
        // change the status code to 500 (HTTP_INTERNAL_ERROR)
        // and save that there is an error
        $this->response = $this->response->withStatus(500);
        $this->errorFound = true;


        return $this;
    }




    public function writeToResponse($asJson = true) {
        if ($asJson) {
            $this->backendResult = DEVELOPMENT_MODE
                ? json_encode($this->backendResult, JSON_PRETTY_PRINT)
                : json_encode($this->backendResult);
        }


        // Write the string response to the $response body
        $this->response->getBody()->write($this->backendResult);

        // Return the result for Slim output
        return $this->response;
    }



}