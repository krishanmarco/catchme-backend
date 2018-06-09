<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 07/10/2017 */

class EConnectionState {
    const PENDING = 0;
    const CONFIRMED = 1;
    const BLOCKED = 2;
}

class EMediaType {
    const LOCATION_IMAGE = 0;
    const LOCATION_PROFILE_IMAGE = 1;
    const USER_PROFILE_IMAGE = 2;
}

class EGender {
    const FEMALE = 0;
    const MALE = 1;
    const UNKNOWN = 2;
}

class EPrivacy {
    const ONLY_ME = '0';
    const MY_FRIENDS = '1';
    const EVERYONE = '2';
}

class ESystemTempVar {
    const PASSWORD_RECO = 0;
}

class EAccessLevel {
    const USER = 0;
    const ADMIN = 1;
}

class ERelationship {
    const SAME_PERSON = 0;
    const CONNECTED = 1;
    const NOT_CONNECTED = 2;
}
