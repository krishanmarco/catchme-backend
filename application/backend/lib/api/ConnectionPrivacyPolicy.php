<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 15/09/2017 - Fithancer © */

namespace Api\Sec;
use Api\User;
use Api\UserLocations;
use \User as DbUser;

class ConnectionPrivacyPolicy {

    const ConnectionRelationships = [
        0 => 'SAME_PERSON',
        1 => 'CONNECTED',
        2 => 'NOT_CONNECTED'
    ];


    public function __construct(DbUser $requestingDbUser, DbUser $requestedDbUser) {
        $this->requestingDbUser = $requestingDbUser;
        $this->requestedDbUser = $requestedDbUser;

        $this->relationshipKey = $this->calculateRelationship();
        $this->requestedDbUserPrivacy = $this->parsePrivacy();
    }

    /** @var DbUser $requestingDbUser */
    private $requestingDbUser;

    /** @var DbUser $requestedDbUser */
    private $requestedDbUser;

    /** @var int $relationshipKey */
    private $relationshipKey;

    /** @var int[] $requestedDbUserPrivacy */
    private $requestedDbUserPrivacy;





    /** @return User */
    public function applyTo(User $apiDbUser) {

        if ($apiDbUser->locations instanceof UserLocations) {

            if (!$this->allowPreviousLocation())
                $apiDbUser->locations->past = [];

            if (!$this->allowNextLocation())
                $apiDbUser->locations->future = [];
        }


        if (!$this->allowMobileNumber()) {
            unset($apiDbUser->phone);
            // $apiDbUser->phone = null;
        }

        if (!$this->allowEmail()) {
            unset($apiDbUser->email);
            // $apiDbUser->email = null;
        }


        return $apiDbUser;
    }


    private function allowPreviousLocation() {
        return $this->allow($this->requestedDbUserPrivacy[0]);
    }

    private function allowNextLocation() {
        return $this->allow($this->requestedDbUserPrivacy[1]);
    }

    private function allowMobileNumber() {
        return $this->allow($this->requestedDbUserPrivacy[2]);
    }

    private function allowEmail() {
        // return $this->allow($this->requestedDbUserPrivacy[0]);
        return true;
    }




    private function allow($DbUserSetting) {
        return $this->relationshipKey <= $DbUserSetting;
    }

    private function calculateRelationship() {
        $requestedId = $this->requestedDbUser->getId();
        $requestingId = $this->requestingDbUser->getId();


        // 1 => 'SAME_PERSON'
        if ($requestedId == $requestingId)
            return 1;


        // 2 => 'CONNECTED'
        if (in_array($requestedId, $this->requestingDbUser->getFriendIds()))
            return 2;


        // 3 => 'NOT_CONNECTED'
        return 3;

    }

    private function parsePrivacy() {
        $privacy = strval($this->requestedDbUser->getSettingPrivacy());
        $privacy = str_split($privacy);
        $privacy = array_map('intval', $privacy);
        return $privacy;
    }

}