<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Slim;
use \Psr\Http\Message\ServerRequestInterface;
use Psr\Container\ContainerInterface;
use Routes\Accessors\ControllerAccessorAuth;
use Routes\Accessors\ControllerAccessorUnauth;


abstract class SlimAttrGet {
    
    const FIELD_AUTHENTICATED_CONTROLLER = 'fieldAuthenticatedController';
    const FIELD_UNAUTHENTICATED_CONTROLLER = 'fieldUnauthenticatedController';
    const FIELD_INPUT_DATA = 'fieldInputData';


    public static function putAuthenticatedController(ContainerInterface $container,
                                                      ControllerAccessorAuth $controller) {
        $container[self::FIELD_AUTHENTICATED_CONTROLLER] = $controller;
        return $container;
    }

    /** @return ControllerAccessorAuth */
    public static function getAuthenticatedController(ContainerInterface $container) {
        return $container->get(self::FIELD_AUTHENTICATED_CONTROLLER);
    }




    public static function putUnAuthenticatedController(ContainerInterface $container,
                                                        ControllerAccessorUnauth $controller) {
        $container[self::FIELD_UNAUTHENTICATED_CONTROLLER] = $controller;
        return $container;
    }

    /** @return ControllerAccessorUnauth */
    public static function getUnAuthenticatedController(ContainerInterface $container) {
        return $container->get(self::FIELD_UNAUTHENTICATED_CONTROLLER);
    }




    public static function putInputData(ServerRequestInterface $request, $data) {
        return $request->withAttribute(self::FIELD_INPUT_DATA, $data);
    }

    public static function getInputData(ServerRequestInterface $request) {
        return $request->getAttribute(self::FIELD_INPUT_DATA);
    }

}