<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Location\Accounts;
use Propel\Runtime\Exception\PropelException as PropelException;
use Location as DbLocation;
use LocationAddress as DbLocationAddress;
use Slim\Exception\ApiException;
use User as DbUser;
use Api\FormLocationRegister as ApiFormLocationRegister;
use Api\LocationAddress as ApiLocationAddress;
use R;

class LocationRegistration {

    public function __construct(DbUser $inserter) {
        $this->location = new DbLocation();
        $this->location->setAdmin($inserter);
    }

    /** @var DbLocation $location */
    private $location;
    public function getLocation() { return $this->location; }




    public function register(ApiFormLocationRegister $formLocationRegister) {
        $this->location->setName($formLocationRegister->name);
        $this->location->setDescription($formLocationRegister->description);
        $this->location->setEmail($formLocationRegister->email);
        $this->location->setCapacity($formLocationRegister->capacity);
        $this->location->setPhone($formLocationRegister->phone);
        $this->location->setTimingsArray($formLocationRegister->timings);

        /** @var ApiLocationAddress $apiLocationAddress */
        $dbLocationAddress = new DbLocationAddress();
        $apiLocationAddress = $formLocationRegister->address;
        $dbLocationAddress->setCountry($apiLocationAddress->country);
        $dbLocationAddress->setState($apiLocationAddress->state);
        $dbLocationAddress->setCity($apiLocationAddress->city);
        $dbLocationAddress->setTown($apiLocationAddress->town);
        $dbLocationAddress->setPostcode($apiLocationAddress->postcode);
        $dbLocationAddress->setAddress($apiLocationAddress->address);
        $dbLocationAddress->setLatLng(\DbLatLng::fromJson($apiLocationAddress->latLng));
        $dbLocationAddress->setGooglePlaceId($apiLocationAddress->googlePlaceId);
        $this->location->setAddress($dbLocationAddress);


        try {
            $this->location->save();

        } catch (PropelException $exception) {
            switch ($exception->getCode()) {
                // duplicate entry, email already exists
                default: throw new ApiException(R::return_error_email_taken);
            }
        }
    }

}