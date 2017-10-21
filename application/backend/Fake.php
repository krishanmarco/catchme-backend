<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 09/09/2017 - Fithancer © */
die("SCRIPT BLOCKED!");
const gender = [0, 1, 2];
const privacy = [
    111, 112, 113, 121, 122, 123, 131, 132, 133,
    211, 212, 213, 221, 222, 223, 231, 232, 233,
    311, 312, 313, 321, 322, 323, 331, 332, 333
];
const connectionState = [0, 1, 2];
const NUMBER_OF_USERS = 5000;
const NUMBER_OF_LOCATIONS = 50;
const MIN_IMAGES_LOCATION = 15;
const MAX_IMAGES_LOCATION = 50;
const MIN_FAVORITES_PER_USER = 30;
const MAX_FAVORITES_PER_USER = NUMBER_OF_LOCATIONS;
const MIN_CONNECTIONS_PER_USER = 30;
const MAX_CONNECTIONS_PER_USER = 150;
const MIN_USER_LOCATION = 10;
const MAX_USER_LOCATION = 15;


function removeValue($array, $value) {
    unset($array[array_search($value, $array)]);
    return array_values($array);
}


$faker = Faker\Factory::create();

// Change the seed to change the generated data
$faker->seed(1234);


 // GENERATE FAKE USERS ********************************************************************
/*
for ($i = 0; $i < NUMBER_OF_USERS; $i++) {
    $user = new User();
    $user->setName($faker->name);
    $user->setPhone($faker->phoneNumber);
    $user->setEmail($faker->unique()->email);
    $user->setGender($faker->randomElement(gender));
    $user->setPrivacy($faker->randomElement(privacy));
    $user->setApiKey('15639419858cff22741882717879297');
    $user->setPassSha256('d701d919533ccae2c7bda1750a005802ef8f67a0464e092b8bbaf31e3a09c1a5');
    $user->setPassSalt('339426');
    $user->setBan(false);
    $user->setSignupTs(time());
    $user->setReputation($faker->randomDigitNotNull);
    $user->setPublicMessage($faker->realText(rand(20, 100)));
    $user->setPictureUrl($faker->imageUrl());
    $user->save();
}*/

// GENERATE FAKE LOCATIONS ********************************************************************
/*
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




    $timings = [];

    for ($p = 0; $p < 7; $p++) {
        $day = [];

        for ($i = 0; $i < 24; $i++) {
            $day[] = $faker->boolean() ? 1 : 0;
        }
        $timings[] = $day;
    }

    $timingsStr = '';
    foreach ($timings as $tt)
        foreach ($tt as $t)
            $timingsStr .= $t;

    $l->setTimingsJson($timingsStr);

    $location->save();
}
*/

/* // GENERATE FAKE LOCATION IMAGES ****************************************************************
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
    }
}*/



/* // UPDATE FAKE LOCATION IMAGES ****************************************************************

$uu = LocationImageQuery::create()->find();
foreach ($uu as $locationImage) {
    $locationImage->setInsertedTs(time() + rand(-(3 * 60 * 60), 0));
    $locationImage->save();
}

*/
/* // GENERATE FAKE USER_CONNECTIONS ********************************************************************

$globalConnections = [];
$globalConnections = json_decode(file_get_contents(__DIR__ . '/temp'), true);
for ($i = 4000; $i < 5000; $i++) {
//for ($i = 0; $i < NUMBER_OF_USERS; $i++) {

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
*/

/* // GENERATE FAKE USER_FAVORITES ********************************************************************

 for ($i = 2500; $i < 5000; $i++) {
//for ($i = 0; $i < NUMBER_OF_USERS; $i++) {

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
*/


/* // GENERATE FAKE USER_LOCATION ********************************************************************

 for ($i = 2500; $i < 5000; $i++) {
// for ($i = 0; $i < NUMBER_OF_USERS; $i++) {

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
}*/


 /*// UPDATE FAKE USER_LOCATION TIMESTAMPS

$uu = UserLocationQuery::create()->find();
foreach ($uu as $userLocation) {
    $from = time() + rand(-(24 * 60 * 60), (24 * 60 * 60));
    $userLocation->setFromTs($from);
    $userLocation->setUntilTs($from + rand(2 * 60 * 60, 5 * 60 * 60));
    $userLocation->save();
}

*/



 // GENERATE FAKE USER_LOCATION_EXPIRED ********************************************************************
/*
 for ($i = 4000; $i < 5000; $i++) {
// for ($i = 0; $i < NUMBER_OF_USERS; $i++) {

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
*/