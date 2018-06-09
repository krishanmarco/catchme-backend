<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 12/09/2017 */

namespace Slim\Middleware;

use Context\Context;
use Psr\Container\ContainerInterface;

class MiddlewareContext {
    const HEADER_ACCEPT_LANGUAGE = 'Accept-Language';
    const HEADER_GEOLOCATION = 'Geolocation';

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

        // Set the context language
        if ($request->hasHeader(self::HEADER_ACCEPT_LANGUAGE))
            Context::setRequestLocale($request->getHeader(self::HEADER_ACCEPT_LANGUAGE)[0]);

        // Set the context geolocation
        if ($request->hasHeader(self::HEADER_GEOLOCATION))
            Context::setGeolocation($request->getHeader(self::HEADER_GEOLOCATION)[0]);

        // Continue processing the request
        $response = $next($request, $response);
        return $response;
    }
}

