<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 09/09/2017 - Fithancer © */

if (!array_key_exists('pw', $_GET) || $_GET['pw'] !== 'S()KD2dk290kdLksK()')
    die("FAIL");
// Script calls
// * fake?pw=S()KD2dk290kdLksK()&function=generateFakeUsers
// * fake?pw=S()KD2dk290kdLksK()&function=generateFakeLocations
// * fake?pw=S()KD2dk290kdLksK()&function=generateFakeLocationImages
// * fake?pw=S()KD2dk290kdLksK()&function=generateFakeUserConnections&p1=0&p2=50 ... &p1=4550&p2=5000
// * fake?pw=S()KD2dk290kdLksK()&function=generateFakeUserFavorites&p1=0&p2=50 ... &p1=4550&p2=5000
// * fake?pw=S()KD2dk290kdLksK()&function=generateFakeUserLocations&p1=0&p2=50 ... &p1=4550&p2=5000
// * fake?pw=S()KD2dk290kdLksK()&function=generateFakeUserLocationExpired&p1=0&p2=50 ... &p1=4550&p2=5000
// * fake?pw=S()KD2dk290kdLksK()&function=update

// Initialize the script
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
set_time_limit(240);
const gender = [0, 1, 2];
const privacy = [
    000, 001, 002, 010, 011, 012, 020, 021, 022,
    100, 101, 102, 110, 111, 112, 120, 121, 122,
    200, 201, 202, 210, 211, 212, 220, 221, 222
];
const connectionState = [0, 1, 2];
const NUMBER_OF_USERS = 5000;
const NUMBER_OF_LOCATIONS = 50;
const MIN_IMAGES_LOCATION = 15;
const MAX_IMAGES_LOCATION = 50;
const MIN_FAVORITES_PER_USER = 30;
const MAX_FAVORITES_PER_USER = NUMBER_OF_LOCATIONS;//
const MIN_CONNECTIONS_PER_USER = 30;
const MAX_CONNECTIONS_PER_USER = 150;
const MIN_USER_LOCATION = 10;
const MAX_USER_LOCATION = 15;



// Script functions
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function removeValue($array, $value) {
    unset($array[array_search($value, $array)]);
    return array_values($array);
}


function generateFakeUsers() {
    $faker = Faker\Factory::create();
    $faker->seed(1234);

    for ($i = 0; $i < NUMBER_OF_USERS; $i++) {
        $user = new User();
        $user->setName($faker->name);
        $user->setPhone($faker->phoneNumber);
        $user->setEmail($faker->unique()->email);
        $user->setGender($faker->randomElement(gender));
        $user->setSettingPrivacy($faker->randomElement(privacy));
        $user->setSettingNotifications($faker->randomElement(privacy));
        $user->setApiKey('15639419858cff22741882717879297');
        $user->setPassSha256('d701d919533ccae2c7bda1750a005802ef8f67a0464e092b8bbaf31e3a09c1a5');
        $user->setPassSalt('339426');
        $user->setBan(false);
        $user->setSignupTs(time());
        $user->setReputation($faker->randomDigitNotNull);
        $user->setPublicMessage($faker->realText(rand(20, 100)));
        $user->setPictureUrl($faker->imageUrl());
        $user->save();
    }
}



function generateFakeLocations() {
    $faker = Faker\Factory::create();
    $faker->seed(1234);

    for ($i = 0; $i < NUMBER_OF_LOCATIONS; $i++) {
        $location = new Location();
        $location->setAdminId($faker->unique()->randomElement(range(1, NUMBER_OF_USERS)));
        $location->setName($faker->company);
        $location->setEmail($faker->unique()->email);
        $location->setDescription($faker->realText(rand(30, 100)));
        $location->setVerified(true);
        $location->setSignupTs(time());
        $location->setPhone($faker->phoneNumber);
        $location->setCapacity(rand(50, 2000));
        $location->setPictureUrl($faker->imageUrl());
        $location->setReputation(rand(345, 19999));

        $locationAddress = new LocationAddress();
        $locationAddress->setCountry($faker->countryCode);
        $locationAddress->setState($faker->citySuffix);
        $locationAddress->setCity($faker->city);
        $locationAddress->setTown($faker->city);
        $locationAddress->setPostcode($faker->postcode);
        $locationAddress->setAddress($faker->address);
        $locationAddress->setLatLng(new DbLatLng($faker->latitude, $faker->longitude));
        $locationAddress->setGooglePlaceId($faker->randomAscii);
        $location->setAddress($locationAddress);

        $timings = '';
        for ($x = 0; $x < 24 * 7; $x++)
            $timings .= $x % 2 == 0 ? '1' : '0';
        $location->setTimings($timings);

        $location->save();
    }
}



function generateFakeLocationImages() {
    $faker = Faker\Factory::create();
    $faker->seed(1234);


    for ($i = 0; $i < NUMBER_OF_LOCATIONS; $i++) {

        $nc = rand(MIN_IMAGES_LOCATION, MAX_IMAGES_LOCATION);
        $id = $i + 1;

        for ($x = 0; $x < $nc; $x++) {
            $locationImg = new LocationImage();
            $locationImg->setLocationId($id);
            $locationImg->setInserterId(rand(1, NUMBER_OF_USERS));
            $locationImg->setInsertedTs(time() + rand(-(3 * 60 * 60), 0));
            $locationImg->setApproved(1);
            $locationImg->save();

            // Download and save the image in the correct dir
            $savePath = strtr(LOCATION_MEDIA_DIR_TPL . '/{IMG_ID}', [
                '{LID}' => $locationImg->getLocationId(),
                '{IMG_ID}' => $locationImg->getId()
            ]);
            file_put_contents($savePath, file_get_contents($faker->image(null, 100, 100)));
        }
    }
}

function updateFakeLocationImages() {

    $uu = LocationImageQuery::create()->find();
    foreach ($uu as $locationImage) {
        $locationImage->setInsertedTs(time() + rand(-(3 * 60 * 60), 0));
        $locationImage->save();
    }
}



function generateFakeUserConnections($start, $end) {
    $faker = Faker\Factory::create();
    $faker->seed(1234);


    $start = intval($start);
    $end = intval($end);

    $globalConnections = $start == 0 ? [] : json_decode(file_get_contents(__DIR__ . '/temp'), true);

    for ($i = $start; $i < $end; $i++) {
        $nc = rand(MIN_CONNECTIONS_PER_USER, MAX_CONNECTIONS_PER_USER);
        $id = $i + 1;
        $possibleConnections = removeValue(range(1, NUMBER_OF_USERS - 1), $id);

        foreach ($globalConnections as $otherUserId => $connections)
            if (in_array($id, $connections))
                $possibleConnections = removeValue($possibleConnections, $otherUserId);

        $globalConnections[$id] = [];
        for ($x = 0; $x < $nc; $x++) {
            $connection = new UserConnection();
            $connection->setUserId($id);

            $con = $faker->randomElement($possibleConnections);
            $connection->setConnectionId($con);
            array_push($globalConnections[$id], $con);
            $possibleConnections = removeValue($possibleConnections, $con);

            $state = $faker->randomElement(connectionState);
            $connection->setState($state);

            $connection->save();
        }
    }
    file_put_contents(__DIR__ . '/temp', json_encode($globalConnections));

    if ($end == NUMBER_OF_USERS)
        unlink(__DIR__ . '/temp');
}



function generateFakeUserFavorites($start, $end) {
    $faker = Faker\Factory::create();
    $faker->seed(1234);


    $start = intval($start);
    $end = intval($end);

    for ($i = $start; $i < $end; $i++) {

        $nc = rand(MIN_FAVORITES_PER_USER, MAX_FAVORITES_PER_USER);
        $id = $i + 1;
        $possibleFavorites = range(1, NUMBER_OF_LOCATIONS - 1);

        for ($x = 0; $x < $nc; $x++) {
            $favorite = new UserLocationFavorite();
            $favorite->setUserId($id);

            $con = $faker->randomElement($possibleFavorites);
            $possibleFavorites = removeValue($possibleFavorites, $con);
            $favorite->setLocationId($con);

            $favorite->save();
        }
    }
}



function generateFakeUserLocations($start, $end) {
    $faker = Faker\Factory::create();
    $faker->seed(1234);


    $start = intval($start);
    $end = intval($end);

    for ($i = $start; $i < $end; $i++) {

        $nc = rand(MIN_USER_LOCATION, MAX_USER_LOCATION);
        $id = $i + 1;
        $possibleLocations = range(1, NUMBER_OF_LOCATIONS - 1);

        for ($x = 0; $x < $nc; $x++) {
            $userLocation = new UserLocation();
            $userLocation->setUserId($id);

            $con = $faker->randomElement($possibleLocations);
            $userLocation->setLocationId($con);
            $possibleLocations = removeValue($possibleLocations, $con);


            $from = time() + rand(-(6 * 60 * 60), (6 * 60 * 60));
            $userLocation->setFromTs($from);
            $userLocation->setUntilTs($from + rand(3 * 60 * 60, 7 * 60 * 60));

            $userLocation->save();
        }
    }
}


function updateFakeUserLocationTimestamps() {
    $faker = Faker\Factory::create();
    $faker->seed(1234);


    $uu = UserLocationQuery::create()->find();
    foreach ($uu as $userLocation) {
        $from = time() + rand(-(24 * 60 * 60), (24 * 60 * 60));
        $userLocation->setFromTs($from);
        $userLocation->setUntilTs($from + rand(2 * 60 * 60, 5 * 60 * 60));
        $userLocation->save();
    }
}



function generateFakeUserLocationExpired($start, $end) {
    $faker = Faker\Factory::create();
    $faker->seed(1234);


    $start = intval($start);
    $end = intval($end);

    for ($i = $start; $i < $end; $i++) {

        $nc = rand(MIN_USER_LOCATION + 5, MAX_USER_LOCATION + 5);
        $id = $i + 1;
        $possibleLocations = range(1, NUMBER_OF_LOCATIONS - 1);

        for ($x = 0; $x < $nc; $x++) {
            $userLocation = new UserLocationExpired();
            $userLocation->setUserId($id);

            $con = $faker->randomElement($possibleLocations);
            $userLocation->setLocationId($con);
            $possibleLocations = removeValue($possibleLocations, $con);


            $from = time() + rand(-((7 * 24) * 60 * 60), -(24 * 60 * 60));
            $userLocation->setFromTs($from);
            $userLocation->setUntilTs($from + rand(3 * 60 * 60, 7 * 60 * 60));

            $userLocation->save();
        }
    }
}


function update() {
    updateFakeLocationImages();
    updateFakeUserLocationTimestamps();
}



function dropAll() {

    function _exec($stmt) {
        $connection = \Propel\Runtime\Propel::getReadConnection(\Map\UserTableMap::DATABASE_NAME);
        $statement = $connection->prepare($stmt);
        $statement->execute();
    }

    _exec('DELETE FROM location');
    _exec('ALTER TABLE location AUTO_INCREMENT = 1;');
    _exec('DELETE FROM location_address');
    _exec('DELETE FROM location_image');
    _exec('ALTER TABLE location_image AUTO_INCREMENT = 1;');
    _exec('DELETE FROM search_location');
    _exec('DELETE FROM search_user WHERE user_id != 1');
    _exec('DELETE FROM system_temp_var');
    _exec('ALTER TABLE system_temp_var AUTO_INCREMENT = 1;');
    _exec('DELETE FROM user WHERE id != 1');
    _exec('ALTER TABLE user AUTO_INCREMENT = 2;');
    _exec('DELETE FROM user_connection');
    _exec('DELETE FROM user_location');
    _exec('ALTER TABLE user_location AUTO_INCREMENT = 1;');
    _exec('DELETE FROM user_location_expired');
    _exec('ALTER TABLE user_location_expired AUTO_INCREMENT = 1;');
    _exec('DELETE FROM user_location_favorite');
    _exec('DELETE FROM user_social');
}



function revertToSimpleTestingState() {

    function _exec($stmt) {
        $connection = \Propel\Runtime\Propel::getReadConnection(\Map\UserTableMap::DATABASE_NAME);
        $statement = $connection->prepare($stmt);
        $statement->execute();
    }

    _exec('DELETE FROM location WHERE id > 5');
    _exec('ALTER TABLE location AUTO_INCREMENT = 6;');
    _exec('DELETE FROM location_address WHERE location_id > 5');
    _exec('DELETE FROM location_image WHERE location_id > 5 AND inserter_id > 5');
//    _exec('ALTER TABLE location_image AUTO_INCREMENT = 6;');
    _exec('DELETE FROM search_location WHERE location_id > 5');
    _exec('DELETE FROM search_user WHERE user_id > 5');
    _exec('DELETE FROM system_temp_var');
    _exec('ALTER TABLE system_temp_var AUTO_INCREMENT = 1;');
    _exec('DELETE FROM user WHERE id > 5');
    _exec('ALTER TABLE user AUTO_INCREMENT = 6;');
    _exec('DELETE FROM user_connection WHERE user_id > 5 AND connection_id > 5');
    _exec('DELETE FROM user_location WHERE user_id > 5 AND location_id > 5');
//    _exec('ALTER TABLE user_location AUTO_INCREMENT = 6;');
    _exec('DELETE FROM user_location_expired');
    _exec('ALTER TABLE user_location_expired AUTO_INCREMENT = 1;');
    _exec('DELETE FROM user_location_favorite WHERE user_id > 5 AND location_id > 5');
    _exec('DELETE FROM user_social');
}

// Bash terraform
/*
terraform () {
    url="http://www.catchme.krishanmadan.website/api/fake?pw=S()KD2dk290kdLksK()&function=";

    run () {
        echo $1;
        wget $1;
    }

    run $url"generateFakeUsers";
    run $url"generateFakeLocations";
    run $url"generateFakeLocationImages";

    iterate0_100() {
        for i in {0..100}
        do
          start=$(($i * 50));
          end=$(($start + 50));
          run $url$1"&p1="$start"&p2="$end;
        done
    }
    iterate0_100 generateFakeUserConnections;
    iterate0_100 generateFakeUserFavorites;
    iterate0_100 generateFakeUserLocations;
    iterate0_100 generateFakeUserLocationExpired;
    echo "COMPLETED!"
}
*/

// Start the script based on the $_GET params
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$functionToExecute = $_GET['function'];
$functionParam1 = array_key_exists('p1', $_GET) ? $_GET['p1'] : null;
$functionParam2 = array_key_exists('p2', $_GET) ? $_GET['p2'] : null;
$functionParam3 = array_key_exists('p3', $_GET) ? $_GET['p3'] : null;
$functionToExecute($functionParam1, $functionParam2, $functionParam3);
die("SUCCESS");

