<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Models\Location\Accounts;
use Propel\Runtime\Exception\PropelException as PropelException;
use Location as DbLocation;
use LocationAddress as DbLocationAddress;
use Slim\Exception\Api400;
use Slim\Http\UploadedFile;
use User as DbUser;
use Api\FormLocationRegister as ApiFormLocationRegister;
use LatLng;
use R;

class LocationRegistration {

    public function __construct(DbUser $inserter) {
        $this->location = new DbLocation();
        $this->location->setAdmin($inserter);
    }

    /** @var DbLocation $location */
    private $location;
    public function getLocation() { return $this->location; }




    public function register(ApiFormLocationRegister $formLocationRegister, $uploadedFile = null) {
        $this->location->setName($formLocationRegister->name);
        $this->location->setDescription($formLocationRegister->description);
        $this->location->setEmail($formLocationRegister->email);
        $this->location->setCapacity($formLocationRegister->capacity);
        $this->location->setPhone($formLocationRegister->phone);
        $this->location->setTimings($formLocationRegister->timings);
        $this->location->setSignupTs(time());
        $this->location->setVerified(LOCATION_DEFAULT_VERIFIED);

        if ($uploadedFile instanceof UploadedFile) {
            $this->location->trySetAvatarFromFile($uploadedFile);
        }

        $dbLocationAddress = new DbLocationAddress();
        $dbLocationAddress->setCountry($formLocationRegister->country);
        $dbLocationAddress->setState($formLocationRegister->state);
        $dbLocationAddress->setCity($formLocationRegister->city);
        $dbLocationAddress->setTown($formLocationRegister->town);
        $dbLocationAddress->setPostcode($formLocationRegister->postcode);
        $dbLocationAddress->setAddress($formLocationRegister->address);
        $dbLocationAddress->setLatLng(LatLng::fromObject($formLocationRegister->latLng));
        $dbLocationAddress->setGooglePlaceId($formLocationRegister->googlePlaceId);
        $this->location->setAddress($dbLocationAddress);

        try {
            $this->location->save();

        } catch (PropelException $exception) {
            switch ($exception->getCode()) {
                // duplicate entry, email already exists
                default: throw new Api400(R::return_error_email_taken, null, $exception);
            }
        }
    }

}