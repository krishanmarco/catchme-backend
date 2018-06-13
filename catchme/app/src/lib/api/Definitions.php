<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Api;

use Api\ApiRules as Rule;

class FormUserLogin {
    public $email = [Rule::ruleIsset => [], Rule::ruleEmail => []];
    public $password = [Rule::ruleIsset => [], Rule::rulePassword => []];
}

class FormUserSocialLogin {
    public $token = [Rule::ruleIsset => []];
}

class FormUserRegister {
    public $name = [Rule::ruleIsset => [], Rule::ruleMediumString => []];
    public $email = [Rule::ruleIsset => [], Rule::ruleEmail => []];
    public $password = [Rule::ruleIsset => [], Rule::rulePassword => []];
}

class FormLocationRegister {
    public $name = [Rule::ruleIsset => [], Rule::ruleMediumString => []];
    public $description = [];
    public $email = [Rule::ruleIsset => [], Rule::ruleEmail => []];
    public $capacity = [Rule::ruleIsset => [], Rule::ruleInt => []];
    public $phone = [Rule::ruleIsset => [], Rule::rulePhone => []];
    public $timings = [Rule::ruleIsset => [], Rule::ruleLongString => [168, 168]];
    public $country = [Rule::ruleIsset => [], Rule::ruleCountry => []];
    public $state = [Rule::ruleCityTownState => []];
    public $city = [Rule::ruleCityTownState => []];
    public $town = [Rule::ruleCityTownState => []];
    public $postcode = [Rule::ruleIsset => [], Rule::rulePostcode => []];
    public $address = [Rule::ruleIsset => [], Rule::ruleAddress => []];
    public $latLng = [Rule::ruleLatLng => []];
    public $googlePlaceId = [Rule::ruleGooglePlaceId => []];
}

class FormChangePassword {
    public $passwordPrevious = [Rule::ruleIsset => [], Rule::rulePassword => []];
    public $passwordNext = [Rule::ruleIsset => [], Rule::rulePassword => []];
    public $passwordConfirmNext = [Rule::ruleIsset => [], Rule::rulePassword => []];
}

class FormFeaturedAdAdd {
    public $title = [Rule::ruleIsset => [], Rule::ruleMediumString => []];
    public $locationId = [Rule::ruleIsset => [], Rule::ruleId => []];
    public $image = [Rule::ruleIsset => [], Rule::ruleUrl => []];
    public $subTitle = [Rule::ruleMediumString => []];
    public $expiry = [Rule::ruleTimestamp => []];
}

class SearchStrings {
    public $queries = [Rule::ruleIsset => []];
}

class ApiMetadata {

    /** @var Location[] */
    public $locations = [];

    /** @var User[] */
    public $users = [];

    /** @var UserLocationStatus[] */
    public $userLocations = [];
}

class UserLocationStatus {
    public $id = [Rule::ruleId => []];
    public $locationId = [Rule::ruleIsset => [], Rule::ruleId => []];
    public $fromTs = [Rule::ruleIsset => [], Rule::ruleTimestamp => []];
    public $untilTs = [Rule::ruleIsset => [], Rule::ruleTimestamp => []];
}

class Location {
    public $id = [Rule::ruleId => []];
    public $adminId = [Rule::ruleId => []];
    public $signupTs = [Rule::ruleTimestamp => []];
    public $verified = [Rule::ruleBool => []];
    public $name = [Rule::ruleMediumString => []];
    public $description = [Rule::ruleLongString => []];
    public $capacity = [Rule::ruleInt => []];
    public $pictureUrl = [Rule::ruleUrl => []];
    public $reputation = [Rule::ruleInt => []];
    public $email = [Rule::ruleEmail => []];
    public $phone = [Rule::rulePhone => []];
    public $timings = [Rule::ruleLongString => [168, 168]];
    public $imageUrls = [Rule::ruleUrls => []];
    public $country = [Rule::ruleCountry => []];
    public $state = [Rule::ruleCityTownState => []];
    public $city = [Rule::ruleCityTownState => []];
    public $town = [Rule::ruleCityTownState => []];
    public $postcode = [Rule::rulePostcode => []];
    public $address = [Rule::ruleAddress => []];
    public $latLng = [Rule::ruleLatLng => []];
    public $googlePlaceId = [Rule::ruleGooglePlaceId => []];

    public $peopleMenCount = [Rule::ruleInt => []];
    public $peopleWomenCount = [Rule::ruleInt => []];
    public $peopleTotalCount = [Rule::ruleInt => []];

    public $connectionsPastIds = [];
    public $connectionsNowIds = [];
    public $connectionsFutureIds = [];

    /** @var ApiMetadata */
    public $metadata = [];
}

class User {
    public $id = [Rule::ruleId => []];
    public $name = [Rule::ruleMediumString => []];
    public $email = [Rule::ruleEmail => []];
    public $apiKey = [];
    public $ban = [Rule::ruleBool => []];
    public $settingPrivacy = [Rule::ruleMediumString => []];
    public $settingNotifications = [Rule::ruleMediumString => []];
    public $signupTs = [Rule::ruleTimestamp => []];
    public $gender = [Rule::ruleEGender => []];
    public $reputation = [Rule::rulePercentage => []];
    public $phone = [Rule::rulePhone => []];
    public $publicMessage = [Rule::ruleLongString => []];
    public $pictureUrl = [Rule::ruleUrl => []];

    public $locationsAdminIds = [];
    public $locationsFavoriteIds = [];
    public $locationsTopIds = [];
    public $locationsUserLocationIds = [];

    public $connectionsFriendIds = [];
    public $connectionsPendingIds = [];
    public $connectionsRequestIds = [];
    public $connectionsBlockedIds = [];

    /** @var ApiMetadata */
    public $metadata = [];
}

class ExceptionResponse {
    public $errorCode = [Rule::ruleIsset => [], Rule::ruleInt => []];
    public $logMessage = [Rule::ruleIsset => [], Rule::ruleLongString => []];
    public $errors = [];
    public $_ = [];
}


