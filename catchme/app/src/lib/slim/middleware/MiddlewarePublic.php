<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 12/09/2017 */

namespace Slim\Middleware;

use Psr\Container\ContainerInterface;
use Routes\Accessors\ControllerAccessorUnauth;
use Slim\SlimAttrGet;

class MiddlewarePublic {

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /** @var ContainerInterface */
    public $container;

    /**
     * @param  \Psr\Http\Message\ServerRequestInterface $request PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface $response PSR7 response
     * @param  callable $next Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next) {
        $controller = new ControllerAccessorUnauth();
        $this->container = SlimAttrGet::putUnAuthenticatedController($this->container, $controller);

        // Continue processing the request
        $response = $next($request, $response);
        return $response;
    }
}

