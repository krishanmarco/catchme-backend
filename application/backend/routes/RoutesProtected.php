<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 12/09/2017 - Fithancer © */

namespace Routes;
use \Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use Routes\Accessors\ControllerAccessorUnauth;
use Slim\SlimAttrGet;
use Slim\SlimOutput;
use Slim\Http\Stream;



class RoutesProtected {

    public function __construct(ContainerInterface $container) {
        $this->controller = SlimAttrGet::getUnAuthenticatedController($container);
    }


    /** @var ControllerAccessorUnauth $controller */
    private $controller;




    const accountsLogin = RoutesProtected::class . ':accountsLogin';

    public function accountsLogin(ServerRequestInterface $request, ResponseInterface $response) {
        $result = $this->controller->accounts()->catchMeLogin(SlimAttrGet::getInputData($request));
        return SlimOutput::buildAndWrite($response, $result);
    }



    const accountsLoginFacebook = RoutesProtected::class . ':accountsLoginFacebook';

    public function accountsLoginFacebook(ServerRequestInterface $request, ResponseInterface $response) {
        $result = $this->controller->accounts()->facebookLogin(SlimAttrGet::getInputData($request));
        return SlimOutput::buildAndWrite($response, $result);
    }



    const accountsLoginGoogle = RoutesProtected::class . ':accountsLoginGoogle';

    public function accountsLoginGoogle(ServerRequestInterface $request, ResponseInterface $response) {
        $result = $this->controller->accounts()->googleLogin(SlimAttrGet::getInputData($request));
        return SlimOutput::buildAndWrite($response, $result);
    }



    const accountsRegister = RoutesProtected::class . ':accountsRegister';

    public function accountsRegister(ServerRequestInterface $request, ResponseInterface $response) {
        $result = $this->controller->accounts()->register(SlimAttrGet::getInputData($request));
        return SlimOutput::buildAndWrite($response, $result);
    }



    const accountsPasswordChange = RoutesProtected::class . ':accountsPasswordChange';

    public function accountsPasswordChange(ServerRequestInterface $request, ResponseInterface $response) {
        $result = $this->controller->accounts()->changePassword(SlimAttrGet::getInputData($request));
        return SlimOutput::buildAndWrite($response, $result);
    }



    const mediaGetTypeIdItemIdImageId = RoutesProtected::class . ':mediaGetTypeIdItemIdImageId';

    public function mediaGetTypeIdItemIdImageId(ServerRequestInterface $request, ResponseInterface $response, $args) {

        $resource = $this->controller->mediaGet()
            ->getBasedOnType($args['typeId'], $args['itemId'], $args['imageId']);

        return $response
            //->withHeader('Content-Type', 'application/force-download')
            //->withHeader('Content-Type', 'application/octet-stream')
            //->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Type', 'image/jpeg')
            ->withHeader('Content-Transfer-Encoding', 'binary')
            ->withBody(new Stream($resource));
    }


}