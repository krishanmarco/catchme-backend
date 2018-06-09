<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 17/09/2017 */

namespace Models\Location\Accounts;
use LocationQuery;
use Location as DbLocation;
use Slim\Exception\Api400;
use Slim\Http\UploadedFile;
use User as DbUser;
use Api\Location as ApiLocation;
use FileUploader;
use R;


class LocationEditProfile {

    public function __construct(DbUser $user, $locationId) {
        $this->location = LocationQuery::create()->findPk($locationId);

        if ($user->getId() != $this->location->getAdminId())
            throw new Api400(R::return_error_not_allowed);

    }


    /** @var DbLocation $location */
    private $location;

    public function getLocation() {
        return $this->location;
    }


    /** @return LocationEditProfile */
    public function userEdit(ApiLocation $apiLocation, $uploadedFile = null) {

        if (isset($apiLocation->description))
            $this->location->setDescription($apiLocation->description);

        if (isset($apiLocation->capacity))
            $this->location->setCapacity($apiLocation->capacity);

        if ($uploadedFile instanceof UploadedFile) {
            $this->location->trySetAvatarFromFile($uploadedFile);
        }

        if (isset($apiLocation->email))
            $this->location->setEmail($apiLocation->email);

        if (isset($apiLocation->phone))
            $this->location->setPhone($apiLocation->phone);

        if (isset($apiLocation->timings))
            $this->location->setTimings($apiLocation->timings);

        if ($uploadedFile instanceof UploadedFile) {
            $this->location->trySetAvatarFromFile($uploadedFile);
        }

        return $this;
    }


    /** @return LocationEditProfile */
    public function superUserEdit(ApiLocation $apiLocation, $uploadedFile = null) {
        $this->userEdit($apiLocation, $uploadedFile);

        if (isset($apiLocation->adminId))
            $this->location->setAdminId($apiLocation->adminId);

        if (isset($apiLocation->verified))
            $this->location->setVerified($apiLocation->verified);

        if (isset($apiLocation->name))
            $this->location->setName($apiLocation->name);

        if (isset($apiLocation->reputation))
            $this->location->setReputation($apiLocation->reputation);

        return $this;
    }

    /** @return LocationEditProfile */
    public function save() {
        $this->location->save();
        return $this;
    }


}