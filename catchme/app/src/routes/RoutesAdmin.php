<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 12/09/2017 */

namespace Routes;

use \Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use Routes\Accessors\ControllerAccessorAuth;
use Routes\Accessors\ControllerAccessorAuthAdmin;
use Slim\SlimAttrGet;
use Slim\SlimOutput;


class RoutesAdmin {

    public function __construct(ContainerInterface $container) {
        $this->userController = SlimAttrGet::getAuthenticatedController($container);
        $this->adminController = SlimAttrGet::getAuthenticatedAdminController($container);
    }

    /** @var ControllerAccessorAuth $userController */
    private $userController;

    /** @var ControllerAccessorAuthAdmin $adminController */
    private $adminController;




    const sendFeaturedAdAttendanceRequest = RoutesAdmin::class . ':sendFeaturedAdAttendanceRequest';

    public function sendFeaturedAdAttendanceRequest(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $result = $this->adminController->asAdmin()->sendFeaturedAdAttendanceRequest(
            SlimAttrGet::getInputData($request)
        );
        return SlimOutput::buildAndWrite($response, $result);
    }

}