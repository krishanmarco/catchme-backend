<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Slim;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Routes\Accessors\ControllerAccessorAuth;
use Routes\Accessors\ControllerAccessorAuthAdmin;
use Routes\Accessors\ControllerAccessorUnauth;


abstract class SlimAttrGet {

    const FIELD_AUTHENTICATED_CONTROLLER = 'fieldAuthenticatedController';
    const FIELD_UNAUTHENTICATED_CONTROLLER = 'fieldUnauthenticatedController';
    const FIELD_AUTHENTICATED_ADMIN_CONTROLLER = 'fieldAuthenticatedAdminController';
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


    public static function putAuthenticatedAdminController(ContainerInterface $container,
                                                           ControllerAccessorAuthAdmin $controller) {
        $container[self::FIELD_AUTHENTICATED_ADMIN_CONTROLLER] = $controller;
        return $container;
    }

    /** @return ControllerAccessorAuthAdmin */
    public static function getAuthenticatedAdminController(ContainerInterface $container) {
        return $container->get(self::FIELD_AUTHENTICATED_ADMIN_CONTROLLER);
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