<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 12/09/2017 - Fithancer © */

namespace Slim\Middleware;

use Mobile\Auth\Authentication as MobileAuthHelper;
use Mobile\Auth\MobileUserAuth as MobileUserAuthHelper;
use Psr\Container\ContainerInterface;
use Routes\Accessors\ControllerAccessorAuth;
use Routes\Accessors\ControllerAccessorAuthAdmin;
use Routes\Accessors\ControllerAccessorUnauth;
use Slim\SlimAttrGet;
use EAccessLevel;


class MiddlewareAuth {

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
        $authToken = $request->getHeader('HTTP_AUTHORIZATION')[0];

        // Authenticate this user
        $authenticate = new MobileAuthHelper($authToken);

        // If authentication was unsuccessful,
        // return HTTP status code 401, unauthorized
        if (!$authenticate->authenticate())
            return $response->withStatus(401, "Invalid authentication");

        // partial-authentication is successful create an
        // un-authenticated controller and set it into the request
        $controller = new ControllerAccessorUnauth();
        $this->container = SlimAttrGet::putUnAuthenticatedController($this->container, $controller);

        // Continue processing the request
        $response = $next($request, $response);

        return $response;
    }


}


class MiddlewareUserAuth {

    public function __construct(ContainerInterface $container, $accessLevel = EAccessLevel::USER) {
        $this->container = $container;
        $this->accessLevel = $accessLevel;
    }

    /** @var ContainerInterface */
    public $container;

    /** @var int<EAccessLevel> $accessLevel */
    public $accessLevel;

    /**
     * @param  \Psr\Http\Message\ServerRequestInterface $request PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface $response PSR7 response
     * @param  callable $next Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next) {
        $authToken = $request->getHeader('HTTP_AUTHORIZATION')[0];

        // Authenticate this user
        $authenticate = new MobileUserAuthHelper($authToken, $this->accessLevel);

        // If authentication was unsuccessful,
        // return HTTP status code 401, unauthorized
        if (!$authenticate->authenticate())
            return $response->withStatus(401, "Invalid authentication");

        // Authentication is successful
        // set the user object as an attribute
        $userController = new ControllerAccessorAuth($authenticate->getVerifiedUser());
        $this->container = SlimAttrGet::putAuthenticatedController($this->container, $userController);

        if ($this->accessLevel >= EAccessLevel::ADMIN) {
            $adminController = new ControllerAccessorAuthAdmin($authenticate->getVerifiedUser());
            $this->container = SlimAttrGet::putAuthenticatedAdminController($this->container, $adminController);
        }

        // Continue processing the request
        $response = $next($request, $response);

        return $response;
    }


}

