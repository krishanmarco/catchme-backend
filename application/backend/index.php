<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Catch Me 1.0 © */

use \Routes\RoutesAdmin;
use \Routes\RoutesPrivate;
use \Routes\RoutesPublic;
use \Routes\RoutesProtected;
use \Slim\Middleware\MiddlewareValidator;
use \Slim\Middleware\MiddlewareUserAuth;
use \Slim\Middleware\MiddlewarePublic;
use \Slim\Middleware\MiddlewareAuth;
use \Slim\Exception\ApiExceptionHandler;


// Important
// Order counts
//  *) /config/Config.php precedes /lib/propel/generated-conf/config.php
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/lib/Tools.php';
require_once __DIR__ . '/lib/ext/ResizeImage.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/lib/propel/generated-conf/config.php';



$app = new \Slim\App([
    'settings' => ['displayErrorDetails' => true]
]);

$app->getContainer()['errorHandler'] = function ($c) {
    return new ApiExceptionHandler();
};



/** Development
 * -----------------------------------------------------------------
 */
$app->get('/fake', function () {
    require_once __DIR__ . '/scripts/Fake.php';
});



/** Unauthenticated web
 * -----------------------------------------------------------------
 * Public access, no api key verification
 */
$app->group('', function () use ($app) {
    $app->get('/meta/time', RoutesPublic::time);
    $app->get('/meta/token/{uid:[-0-9]+}/{key}', RoutesPublic::token);
});

$app->group('', function () use ($app) {

    // /accounts/user/{uid}/password/reset?token=abcdef
    $app->get('/accounts/user/{uid}/password/reset', RoutesProtected::accountsPasswordReset);
})->add(new MiddlewarePublic($app->getContainer()));;


/** Anonymous authenticated web
 * -----------------------------------------------------------------
 * Protected access, api key verification, no user verification
 */
$app->group('', function () use ($app) {

    $app->post('/accounts/login', RoutesProtected::accountsLogin)
        ->add(new MiddlewareValidator(Api\FormUserLogin::class));

    $app->post('/accounts/login/facebook', RoutesProtected::accountsLoginFacebook)
        ->add(new MiddlewareValidator(Api\FormUserSocialLogin::class));

    $app->post('/accounts/login/google', RoutesProtected::accountsLoginGoogle)
        ->add(new MiddlewareValidator(Api\FormUserSocialLogin::class));

    $app->post('/accounts/register', RoutesProtected::accountsRegister)
        ->add(new MiddlewareValidator(Api\FormUserRegister::class));

    $app->post('/accounts/user/{uid}/password/change', RoutesProtected::accountsPasswordChange)
        ->add(new MiddlewareValidator(Api\FormChangePassword::class));

    $app->get('/accounts/user/{email}/password/recover', RoutesProtected::accountsPasswordRecover);

    $app->get('/media/get/{typeId:[0-9]+}/{itemId:[0-9]+}/{imageId:[0-9]+}[{ext:.*}]', RoutesProtected::mediaGetTypeIdItemIdImageId);

})->add(new MiddlewareAuth($app->getContainer()));




/** User authenticated web
 * -----------------------------------------------------------------
 * Private access, api and user key verification
 */
$app->group('', function () use ($app) {
    $app->get('/locations/{lid:[0-9]+}', RoutesPrivate::locationsLid);
    $app->get('/locations/{lid:[0-9]+}/profile', RoutesPrivate::locationsLidProfile);
    $app->get('/search/{query}/locations', RoutesPrivate::searchQueryLocations);
    $app->get('/search/{query}/users', RoutesPrivate::searchQueryUsers);
    $app->get('/suggest/{seed:[-0-9]+}/locations', RoutesPrivate::suggestSeedLocations);
    $app->get('/suggest/{seed:[-0-9]+}/users', RoutesPrivate::suggestSeedUsers);
    $app->get('/users/{uid:[0-9]+}', RoutesPrivate::usersUid);
    $app->get('/users/{uid:[0-9]+}/profile', RoutesPrivate::usersUidProfile);

    $app->get('/user', RoutesPrivate::user);
    $app->get('/user/firebase-jwt', RoutesPrivate::userFirebaseJwt);
    $app->get('/user/profile', RoutesPrivate::userProfile);
    $app->get('/user/profile/edit/firebase/{token}', RoutesPrivate::userProfileEditFirebaseToken);
    $app->get('/user/connections/add/{uid:[0-9]+}', RoutesPrivate::userConnectionsAddUid);
    $app->get('/user/connections/accept/{uid:[0-9]+}', RoutesPrivate::userConnectionsAcceptUid);
    $app->get('/user/connections/block/{uid:[0-9]+}', RoutesPrivate::userConnectionsBlockUid);
    $app->get('/user/status', RoutesPrivate::userStatus);
    $app->get('/user/status/del/{tid:[0-9]+}', RoutesPrivate::userStatusDelTid);
    $app->get('/user/locations/favorites/add/{lid:[0-9]+}', RoutesPrivate::userLocationsFavoritesAddLid);
    $app->get('/user/locations/favorites/del/{lid:[0-9]+}', RoutesPrivate::userLocationsFavoritesDelLid);

    $app->post('/search/users', RoutesPrivate::searchUsers)
        ->add(new MiddlewareValidator(Api\SearchStrings::class));

    $app->post('/user/profile/edit', RoutesPrivate::userProfileEdit)
        ->add(new MiddlewareValidator(Api\User::class));

    $app->post('/user/status/add', RoutesPrivate::userStatusAdd)
        ->add(new MiddlewareValidator(Api\UserLocationStatus::class));

    $app->post('/user/locations/administrating/edit/-1', RoutesPrivate::userLocationsAdministratingRegister)
        ->add(new MiddlewareValidator(Api\FormLocationRegister::class));;

    $app->post('/user/locations/administrating/edit/{lid:[0-9]+}', RoutesPrivate::userLocationsAdministratingEditLid)
        ->add(new MiddlewareValidator(Api\Location::class));;

    $app->post('/media/add/{typeId:[0-9]+}/{itemId:[0-9]+}', RoutesPrivate::mediaAddTypeIdItemId);

})->add(new MiddlewareUserAuth($app->getContainer()));




/** User authenticated as admin
 * -----------------------------------------------------------------
 * Private Admin access, api and user key and user access verification
 */
$app->group('', function () use ($app) {

    $app->post('/admin/featuredAds/sendAttendanceRequest', RoutesAdmin::sendFeaturedAdAttendanceRequest)
        ->add(new MiddlewareValidator(Api\FormFeaturedAdAdd::class));

})->add(new MiddlewareUserAuth($app->getContainer(), EAccessLevel::ADMIN));


$app->run();