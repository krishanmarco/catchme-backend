<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 15/09/2017 */

namespace Api\Sec;

use Api\User as ApiUser;
use Api\UserLocations as ApiUserLocations;
use Api\UserLocationStatus as ApiUserLocationStatus;
use User as DbUser;
use ERelationship;

class ConnectionPrivacyPolicy {

    public function __construct(DbUser $requestingDbUser, DbUser $requestedDbUser) {
        $this->requestingUser = $requestingDbUser;
        $this->requestedUser = $requestedDbUser;

        $this->relationshipKey = $this->calculateRelationship();
        $this->requestedDbUserPrivacy = $this->parsePrivacy();
    }

    /** @var DbUser */
    private $requestingUser;

    /** @var DbUser */
    private $requestedUser;

    /** @var int<ERelationship> */
    private $relationshipKey;

    /** @var int<ERelationship>[] */
    private $requestedDbUserPrivacy;

    /** @return ApiUser */
    public function applyTo(ApiUser $apiDbUser) {

        if (!$this->allowMobileNumber())
            unset($apiDbUser->phone);

        if (!$this->allowEmail())
            unset($apiDbUser->email);

        if ($apiDbUser->locations instanceof ApiUserLocations) {
            $newUls = [];

            // Calculate the [past, now, future] locations
            /** @var  $uls ApiUserLocationStatus */
            foreach ($apiDbUser->locations->userLocationStatuses as $uls) {
                $nowTs = time();

                if ($uls->fromTs > $nowTs && !$this->allowNextLocation())
                    continue;

                if ($uls->untilTs < $nowTs && !$this->allowPreviousLocation())
                    continue;

                if ($uls->fromTs <= $nowTs && $uls->untilTs >= $nowTs && !$this->allowCurrentLocation())
                    continue;

                array_push($newUls, $uls);
            }

            $apiDbUser->locations->userLocationStatuses = $newUls;
        }

        return $apiDbUser;
    }

    /** @return boolean */
    private function allowPreviousLocation() {
        return $this->allow($this->requestedDbUserPrivacy[0]);
    }

    /** @return boolean */
    private function allowCurrentLocation() {
        return $this->allow($this->requestedDbUserPrivacy[1]);
    }

    /** @return boolean */
    private function allowNextLocation() {
        return $this->allow($this->requestedDbUserPrivacy[2]);
    }

    /** @return boolean */
    private function allowEmail() {
        return $this->allow($this->requestedDbUserPrivacy[3]);
    }

    /** @return boolean */
    private function allowMobileNumber() {
        return $this->allow($this->requestedDbUserPrivacy[4]);
    }

    /** @return boolean */
    private function allow($setting) {
        return $this->relationshipKey <= $setting;
    }

    /** @return int */
    private function calculateRelationship() {
        $requestedId = $this->requestedUser->getId();
        $requestingId = $this->requestingUser->getId();

        if ($requestedId == $requestingId)
            return ERelationship::CONNECTED;

        if (in_array($requestedId, $this->requestingUser->getFriendIds()))
            return ERelationship::CONNECTED;

        return ERelationship::NOT_CONNECTED;
    }

    /** @return int[] */
    private function parsePrivacy() {
        $privacy = strval($this->requestedUser->getSettingPrivacy());
        $privacy = str_split($privacy);
        $privacy = array_map('intval', $privacy);
        return $privacy;
    }

}