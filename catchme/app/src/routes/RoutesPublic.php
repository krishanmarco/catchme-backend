<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 12/09/2017 */

namespace Routes;

use Mobile\Auth\MobileUserAuth;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class RoutesPublic {

    public function __construct(ContainerInterface $container) {
        // No parameters for this controller
    }


    const time = RoutesPublic::class . ':time';

    public function time(ServerRequestInterface $request, ResponseInterface $response) {
        return $response->getBody()->write(MobileUserAuth::getAuthTokenTime());
    }


    const token = RoutesPublic::class . ':token';

    public function token(ServerRequestInterface $request, ResponseInterface $response, $args) {
        return $response->getBody()->write(MobileUserAuth::buildTokenStr($args['uid'], $args['key']));
    }

}