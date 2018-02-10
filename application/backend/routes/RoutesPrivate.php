<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 12/09/2017 - Fithancer © */

namespace Routes;

use \Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use Routes\Accessors\ControllerAccessorAuth;
use Slim\Exception\ApiException;
use Slim\Http\UploadedFile;
use Slim\SlimAttrGet;
use Slim\SlimOutput;
use Api\User as ApiUser;
use R;
use Psr\Http\Message\UploadedFileInterface;


class RoutesPrivate {

    public function __construct(ContainerInterface $container) {
        $this->controller = SlimAttrGet::getAuthenticatedController($container);
    }


    /** @var ControllerAccessorAuth $controller */
    private $controller;




    const locationsLid = RoutesPrivate::class . ':locationsLid';

    public function locationsLid(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $apiLocation = $this->controller->locations($args['lid'])->get();
        return SlimOutput::buildAndWrite($response, $apiLocation);
    }



    const locationsLidProfile = RoutesPrivate::class . ':locationsLidProfile';

    public function locationsLidProfile(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $apiLocations = $this->controller->locations($args['lid'])->getProfile();
        return SlimOutput::buildAndWrite($response, $apiLocations);
    }



    const searchQueryLocations = RoutesPrivate::class . ':searchQueryLocations';

    public function searchQueryLocations(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $apiLocations = $this->controller->search()->locationsSearch($args['query']);
        return SlimOutput::buildAndWrite($response, $apiLocations);
    }



    const searchQueryUsers = RoutesPrivate::class . ':searchQueryUsers';

    public function searchQueryUsers(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $apiUsers = $this->controller->search()->usersSearch($args['query']);
        return SlimOutput::buildAndWrite($response, $apiUsers);
    }



    const searchUsers = RoutesPrivate::class . ':searchUsers';

    public function searchUsers(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $apiUsers = $this->controller->search()->usersSearchMultiple(
            SlimAttrGet::getInputData($request)
        );
        return SlimOutput::buildAndWrite($response, $apiUsers);
    }



    const suggestSeedLocations = RoutesPrivate::class . ':suggestSeedLocations';

    public function suggestSeedLocations(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $apiLocations = $this->controller->search()->locationsSuggested($args['seed']);
        return SlimOutput::buildAndWrite($response, $apiLocations);
    }



    const suggestSeedUsers = RoutesPrivate::class . ':suggestSeedUsers';

    public function suggestSeedUsers(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $apiUsers = $this->controller->search()->usersSuggested($args['seed']);
        return SlimOutput::buildAndWrite($response, $apiUsers);
    }



    const usersUid = RoutesPrivate::class . ':usersUid';

    public function usersUid(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $apiUser = $this->controller->users(intval($args['uid']))->get();
        return SlimOutput::buildAndWrite($response, $apiUser);
    }



    const usersUidProfile = RoutesPrivate::class . ':usersUidProfile';

    public function usersUidProfile(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $apiUser = $this->controller->users(intval($args['uid']))->getProfile();
        return SlimOutput::buildAndWrite($response, $apiUser);
    }



    const user = RoutesPrivate::class . ':user';

    public function user(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $apiUser = $this->controller->user()->get();
        return SlimOutput::buildAndWrite($response, $apiUser);
    }



    const userFirebaseJwt = RoutesPrivate::class . ':userFirebaseJwt';

    public function userFirebaseJwt(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $userFirebaseJwt = $this->controller->user()->getJwt();
        return SlimOutput::buildAndWrite($response, $userFirebaseJwt, false);
    }



    const userProfile = RoutesPrivate::class . ':userProfile';

    public function userProfile(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $apiUser = $this->controller->user()->getProfile();
        return SlimOutput::buildAndWrite($response, $apiUser);
    }



    const userProfileEdit = RoutesPrivate::class . ':userProfileEdit';

    public function userProfileEdit(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $res = $this->controller->user()->editProfile(SlimAttrGet::getInputData($request));
        return SlimOutput::buildAndWrite($response, $res);
    }



    const userProfileEditFirebaseToken = RoutesPrivate::class . ':userProfileEditFirebaseToken';

    public function userProfileEditFirebaseToken(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $res = $this->controller->user()->editProfileFirebase($args['token']);
        return SlimOutput::buildAndWrite($response, $res);
    }



    const userConnectionsAddUid = RoutesPrivate::class . ':userConnectionsAddUid';

    public function userConnectionsAddUid(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $res = $this->controller->user()->connectionsAddUid($args['uid']);
        return SlimOutput::buildAndWrite($response, $res);
    }



    const userConnectionsAcceptUid = RoutesPrivate::class . ':userConnectionsAcceptUid';

    public function userConnectionsAcceptUid(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $res = $this->controller->user()->connectionsAcceptUid($args['uid']);
        return SlimOutput::buildAndWrite($response, $res);
    }



    const userConnectionsBlockUid = RoutesPrivate::class . ':userConnectionsBlockUid';

    public function userConnectionsBlockUid(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $res = $this->controller->user()->connectionsBlockUid($args['uid']);
        return SlimOutput::buildAndWrite($response, $res);
    }



    const userStatus = RoutesPrivate::class . ':userStatus';

    public function userStatus(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $res = $this->controller->user()->status();
        return SlimOutput::buildAndWrite($response, $res);
    }



    const userStatusAdd = RoutesPrivate::class . ':userStatusAdd';

    public function userStatusAdd(ServerRequestInterface $request, ResponseInterface $response) {
        $res = $this->controller->user()->statusAdd(SlimAttrGet::getInputData($request));
        return SlimOutput::buildAndWrite($response, $res);
    }



    const userStatusDelTid = RoutesPrivate::class . ':userStatusDelTid';

    public function userStatusDelTid(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $res = $this->controller->user()->statusDel($args['tid']);
        return SlimOutput::buildAndWrite($response, $res);
    }



    const userLocationsFavoritesAddLid = RoutesPrivate::class . ':userLocationsFavoritesAddLid';

    public function userLocationsFavoritesAddLid(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $res = $this->controller->user()->locationsFavoritesAdd($args['lid']);
        return SlimOutput::buildAndWrite($response, $res);
    }



    const userLocationsFavoritesDelLid = RoutesPrivate::class . ':userLocationsFavoritesDelLid';

    public function userLocationsFavoritesDelLid(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $res = $this->controller->user()->locationsFavoritesDel($args['lid']);
        return SlimOutput::buildAndWrite($response, $res);
    }



    const userLocationsAdministratingRegister = RoutesPrivate::class . ':userLocationsAdministratingRegister';

    public function userLocationsAdministratingRegister(ServerRequestInterface $request, ResponseInterface $response) {
        $opCode = $this->controller->user()->locationsAdministratingRegister(
            SlimAttrGet::getInputData($request)
        );
        return SlimOutput::buildAndWrite($response, $opCode);
    }



    const userLocationsAdministratingEditLid = RoutesPrivate::class . ':userLocationsAdministratingEditLid';

    public function userLocationsAdministratingEditLid(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $res = $this->controller->user()->locationsAdministratingEditLid(
            SlimAttrGet::getInputData($request),
            $args['lid']
        );
        return SlimOutput::buildAndWrite($response, $res);
    }



    const mediaAddTypeIdItemId = RoutesPrivate::class . ':mediaAddTypeIdItemId';

    public function mediaAddTypeIdItemId(ServerRequestInterface $request, ResponseInterface $response, $args) {
        $uploadedFiles = $request->getUploadedFiles();

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $uploadedFiles['file'];

        if ($uploadedFile == null || $uploadedFile->getError() !== UPLOAD_ERR_OK)
            throw new ApiException(R::return_error_file_upload_invalid);

        $res = $this->controller->media()->addBasedOnType(
            $args['typeId'],
            $args['itemId'],
            $uploadedFile
        );

        return SlimOutput::buildAndWrite($response, $res);
    }




}