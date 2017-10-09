<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 27/08/2017 - Fithancer © */

namespace Slim\Middleware;
use Security\Validator;
use Slim\SlimAttrGet;
use Slim\SlimOutput;


class MiddlewareValidator {

    public function __construct($apiDefInputClass, $apiDefOutputClass = null) {
        $this->apiDefInputClass = $apiDefInputClass;
        $this->apiDefOutputClass = $apiDefOutputClass;
    }



    /** @var string $apiDefInputClass */
    private $apiDefInputClass;

    /** @var string $apiDefOutputClass */
    private $apiDefOutputClass = null;




    /**
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next) {

        // Get the input data from the $request
        $inputData = $request->getParsedBody();

        $validator = new Validator($inputData, $this->apiDefInputClass);

        if (!$validator->validateObject()) {
            // Input data is not valid

            $errorResult = $validator->getResult();

            if (!is_null($this->apiDefOutputClass))
                $errorResult = array_merge((array) new $this->apiDefOutputClass, $errorResult);

            return SlimOutput::buildAndWrite($response, $errorResult);
        }


        // Input data is valid, add the data to the request
        $request = SlimAttrGet::putInputData($request, $validator->getResult());


        // Continue processing the request
        return $next($request, $response);
    }


}