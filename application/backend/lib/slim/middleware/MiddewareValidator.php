<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 27/08/2017 - Fithancer © */

namespace Slim\Middleware;
use Security\Validator;
use Slim\Exception\Api400;
use Slim\SlimAttrGet;
use Slim\SlimOutput;
use R;


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
        // If the input data has been flattened we need to un flatten it
        // on the client the delimiter is '.' but PHP $_POST transforms it to a '_'
        $inputData = array_unflatten($request->getParsedBody(), '_');

        $validator = new Validator($inputData, $this->apiDefInputClass);

        if (!$validator->validateObject()) {
            // Input data is not valid

            $errorResult = $validator->getResult();

            if (!is_null($this->apiDefOutputClass))
                $errorResult = array_merge((array) new $this->apiDefOutputClass, $errorResult);

            throw new Api400(R::return_error_form, $errorResult);
        }

        // Get the result from the validator
        $result = $validator->getResult();

        // Input data is valid, add the data to the request
        $request = SlimAttrGet::putInputData($request, $result);


        // Continue processing the request
        return $next($request, $response);
    }


}